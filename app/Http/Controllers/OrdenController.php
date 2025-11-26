<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\OrdenDetalle;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrdenController extends Controller
{
    /**
     * Listado de órdenes con búsqueda y filtros
     */
    public function index(Request $request)
    {
        $query = Orden::with(['cliente', 'usuario', 'pagos'])
            ->orderBy('fecha_recepcion', 'desc');

        // Búsqueda por número de orden
        if ($request->filled('nro')) {
            $query->where('nro', 'LIKE', '%' . $request->nro . '%');
        }

        // Filtro por cliente
        if ($request->filled('cliente')) {
            $query->whereHas('cliente', function($q) use ($request) {
                $q->where('nombre', 'LIKE', '%' . $request->cliente . '%');
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por forma de pago
        if ($request->filled('forma_pago')) {
            $query->where('forma_pago', $request->forma_pago);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_recepcion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_recepcion', '<=', $request->fecha_hasta);
        }

        $ordenes = $query->paginate(15)->through(function ($orden) {
            return [
                'nro' => $orden->nro,
                'fecha_recepcion' => $orden->fecha_recepcion?->format('d/m/Y'),
                'fecha_listo' => $orden->fecha_listo?->format('d/m/Y'),
                'fecha_entrega' => $orden->fecha_entrega?->format('d/m/Y'),
                'estado' => $orden->estado,
                'forma_pago' => $orden->forma_pago,
                'total' => $orden->total,
                'cliente' => [
                    'id' => $orden->cliente->id,
                    'nombre' => $orden->cliente->nombre,
                    'telefono' => $orden->cliente->telefono,
                ],
                'usuario' => [
                    'id' => $orden->usuario->id,
                    'nombre' => $orden->usuario->nombre,
                    'tipo_usuario' => $orden->usuario->tipo_usuario,
                ],
                'total_pagado' => $orden->totalPagado(),
                'saldo_pendiente' => $orden->saldoPendiente(),
            ];
        });

        return Inertia::render('Ordenes/Index', [
            'ordenes' => $ordenes,
            'filtros' => $request->only(['nro', 'cliente', 'estado', 'forma_pago', 'fecha_desde', 'fecha_hasta']),
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get(['id', 'nombre', 'telefono', 'direccion']);
        $servicios = Servicio::where('estado', true)
            ->orderBy('nombre')
            ->get();

        // Obtener promociones activas para cada servicio
        $serviciosConPromocion = $servicios->map(function ($servicio) {
            $promocion = $servicio->promocionActiva();
            return [
                'id' => $servicio->id,
                'nombre' => $servicio->nombre,
                'descripcion' => $servicio->descripcion,
                'tipo_cobro' => $servicio->tipo_cobro,
                'precio_unitario' => $servicio->precio_unitario,
                'promocion' => $promocion ? [
                    'nombre' => $promocion->nombre,
                    'descuento' => $promocion->descuento,
                ] : null,
            ];
        });

        $fragancias = [
            'Lavanda',
            'Floral',
            'Fresco',
            'Cítrico',
            'Baby (bebé)',
            'Sin Fragancia',
        ];

        return Inertia::render('Ordenes/Create', [
            'clientes' => $clientes,
            'servicios' => $serviciosConPromocion,
            'fragancias' => $fragancias,
            'numeroOrden' => Orden::generarNumeroOrden(),
        ]);
    }

    /**
     * Guardar nueva orden
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:cliente,id',
            'forma_pago' => 'required|in:CONTADO,CREDITO',
            'fecha_vencimiento' => 'nullable|date|after:today',
            'observaciones' => 'nullable|string|max:500',
            'detalles' => 'required|array|min:1',
            'detalles.*.servicio_id' => 'required|exists:servicio,id',
            'detalles.*.peso_kg' => 'nullable|numeric|min:0.01',
            'detalles.*.cantidad' => 'nullable|integer|min:1',
            'detalles.*.fragancia' => 'nullable|string',
            'detalles.*.notas' => 'nullable|string',
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente',
            'cliente_id.exists' => 'El cliente seleccionado no existe',
            'forma_pago.required' => 'Debe seleccionar una forma de pago',
            'forma_pago.in' => 'La forma de pago debe ser CONTADO o CREDITO',
            'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a hoy',
            'detalles.required' => 'Debe agregar al menos un servicio',
            'detalles.min' => 'Debe agregar al menos un servicio',
            'detalles.*.servicio_id.required' => 'Debe seleccionar un servicio',
            'detalles.*.servicio_id.exists' => 'El servicio seleccionado no existe',
            'detalles.*.peso_kg.min' => 'El peso debe ser mayor a 0',
            'detalles.*.cantidad.min' => 'La cantidad debe ser mayor a 0',
        ]);

        // Validar fecha de vencimiento solo si es CREDITO
        if ($validated['forma_pago'] === 'CREDITO' && empty($validated['fecha_vencimiento'])) {
            return back()->withErrors(['fecha_vencimiento' => 'La fecha de vencimiento es obligatoria para crédito'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Generar número de orden
            $nroOrden = Orden::generarNumeroOrden();

            // Obtener usuario actual (puede ser empleado o propietario)
            $usuarioId = Auth::id();

            // Crear orden
            $orden = new Orden();
            $orden->nro = $nroOrden;
            $orden->fecha_recepcion = now();
            $orden->estado = 'PENDIENTE';
            $orden->forma_pago = $validated['forma_pago'];
            $orden->fecha_vencimiento = $validated['forma_pago'] === 'CREDITO' ? $validated['fecha_vencimiento'] : null;
            $orden->cliente_id = $validated['cliente_id'];
            $orden->usuario_id = $usuarioId; // ID del usuario que crea la orden
            $orden->observaciones = $validated['observaciones'] ?? null;

            // Calcular totales
            $subtotalOrden = 0;
            $descuentoOrden = 0;

            $detallesData = [];
            
            foreach ($validated['detalles'] as $detalleData) {
                $servicio = Servicio::find($detalleData['servicio_id']);
                
                // Calcular subtotal según tipo de cobro
                if ($servicio->tipo_cobro === 'KILO') {
                    $peso = $detalleData['peso_kg'] ?? 0;
                    $subtotal = $peso * $servicio->precio_unitario;
                } else { // PIEZA
                    $cantidad = $detalleData['cantidad'] ?? 0;
                    $subtotal = $cantidad * $servicio->precio_unitario;
                }

                // Calcular descuento si hay promoción activa
                $descuento = $servicio->calcularDescuento($subtotal);
                $totalLinea = $subtotal - $descuento;

                $subtotalOrden += $subtotal;
                $descuentoOrden += $descuento;

                $detallesData[] = [
                    'servicio_id' => $servicio->id,
                    'unidad' => $servicio->tipo_cobro === 'KILO' ? 'KILO' : 'PIEZA',
                    'peso_kg' => $servicio->tipo_cobro === 'KILO' ? ($detalleData['peso_kg'] ?? null) : null,
                    'cantidad' => $servicio->tipo_cobro === 'PIEZA' ? ($detalleData['cantidad'] ?? null) : null,
                    'precio_unitario' => $servicio->precio_unitario,
                    'descuento' => $descuento,
                    'fragancia' => $detalleData['fragancia'] ?? null,
                    'notas' => $detalleData['notas'] ?? null,
                    'subtotal' => $subtotal,
                    'total_linea' => $totalLinea,
                ];
            }

            $orden->subtotal = $subtotalOrden;
            $orden->descuento = $descuentoOrden;
            $orden->total = $subtotalOrden - $descuentoOrden;
            $orden->save();

            // Guardar detalles
            foreach ($detallesData as $detalle) {
                $ordenDetalle = new OrdenDetalle($detalle);
                $ordenDetalle->orden_nro = $nroOrden;
                $ordenDetalle->save();
            }

            DB::commit();

            return redirect()->route('ordenes.show', $nroOrden)->with('success', 'Orden creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear la orden: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Mostrar detalle de una orden
     */
    public function show($nro)
    {
        $orden = Orden::with([
            'cliente',
            'usuario',
            'detalles.servicio',
            'pagos'
        ])->findOrFail($nro);

        return Inertia::render('Ordenes/Show', [
            'orden' => [
                'nro' => $orden->nro,
                'fecha_recepcion' => $orden->fecha_recepcion?->format('d/m/Y'),
                'fecha_listo' => $orden->fecha_listo?->format('d/m/Y'),
                'fecha_entrega' => $orden->fecha_entrega?->format('d/m/Y'),
                'estado' => $orden->estado,
                'forma_pago' => $orden->forma_pago,
                'fecha_vencimiento' => $orden->fecha_vencimiento?->format('d/m/Y'),
                'subtotal' => $orden->subtotal,
                'descuento' => $orden->descuento,
                'total' => $orden->total,
                'observaciones' => $orden->observaciones,
                'cliente' => [
                    'id' => $orden->cliente->id,
                    'nombre' => $orden->cliente->nombre,
                    'telefono' => $orden->cliente->telefono,
                    'direccion' => $orden->cliente->direccion,
                ],
                'usuario' => [
                    'id' => $orden->usuario->id,
                    'nombre' => $orden->usuario->nombre,
                    'tipo_usuario' => $orden->usuario->tipo_usuario,
                ],
                'detalles' => $orden->detalles->map(function ($detalle) {
                    return [
                        'id' => $detalle->id,
                        'servicio' => [
                            'id' => $detalle->servicio->id,
                            'nombre' => $detalle->servicio->nombre,
                            'descripcion' => $detalle->servicio->descripcion,
                        ],
                        'unidad' => $detalle->unidad,
                        'peso_kg' => $detalle->peso_kg,
                        'cantidad' => $detalle->cantidad,
                        'precio_unitario' => $detalle->precio_unitario,
                        'descuento' => $detalle->descuento,
                        'fragancia' => $detalle->fragancia,
                        'notas' => $detalle->notas,
                        'subtotal' => $detalle->subtotal,
                        'total_linea' => $detalle->total_linea,
                    ];
                }),
                'pagos' => $orden->pagos->map(function ($pago) {
                    return [
                        'id' => $pago->id,
                        'fecha' => $pago->fecha,
                        'monto' => $pago->monto,
                        'metodo' => $pago->metodo,
                        'referencia' => $pago->referencia,
                    ];
                }),
                'total_pagado' => $orden->totalPagado(),
                'saldo_pendiente' => $orden->saldoPendiente(),
            ],
        ]);
    }

    /**
     * Registrar pago (EFECTIVO o QR)
     */
    public function registrarPago(Request $request, $nro)
    {
        $orden = Orden::findOrFail($nro);

        $validated = $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'metodo' => 'required|in:EFECTIVO,QR',
            'referencia' => 'nullable|string|max:200',
        ], [
            'monto.required' => 'El monto es obligatorio',
            'monto.min' => 'El monto debe ser mayor a 0',
            'metodo.required' => 'El método de pago es obligatorio',
            'metodo.in' => 'El método debe ser EFECTIVO o QR',
        ]);

        // Validar que el monto no exceda el saldo pendiente
        $saldoPendiente = $orden->saldoPendiente();
        if ($validated['monto'] > $saldoPendiente) {
            return back()->withErrors(['monto' => 'El monto no puede exceder el saldo pendiente de Bs. ' . number_format($saldoPendiente, 2)])->withInput();
        }

        try {
            $pago = new Pago();
            $pago->orden_nro = $nro;
            $pago->fecha = now();
            $pago->monto = $validated['monto'];
            $pago->metodo = $validated['metodo'];
            $pago->referencia = $validated['referencia'] ?? null;
            $pago->save();

            // Si el pago completa el total, actualizar estado de la orden
            if ($orden->saldoPendiente() <= 0.01) { // Tolerancia para decimales
                $orden->estado = 'PAGADA';
                $orden->save();
            }

            return back()->with('success', 'Pago registrado exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al registrar el pago: ' . $e->getMessage()])->withInput();
        }
    }
}

