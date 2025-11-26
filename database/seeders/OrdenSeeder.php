<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Orden;
use App\Models\OrdenDetalle;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Servicio;
use Carbon\Carbon;

class OrdenSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Cliente::all();
        $usuarios = Usuario::all();
        $servicios = Servicio::all();

        // Orden 1: Alan Romero - Lavado de ropa 5kg (CONTADO, ENTREGADA)
        $orden1 = Orden::create([
            'nro' => 'BEL-000001',
            'fecha_recepcion' => Carbon::parse('2024-08-28'),
            'fecha_listo' => Carbon::parse('2024-08-29'),
            'fecha_entrega' => Carbon::parse('2024-08-29'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CONTADO',
            'fecha_vencimiento' => null,
            'subtotal' => 65.00,
            'descuento' => 0.00,
            'total' => 65.00,
            'cliente_id' => 1, // Alan Romero
            'usuario_id' => 2, // Usuario empleado
            'observaciones' => 'Perfume Lavanda'
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000001',
            'servicio_id' => $servicios->where('nombre', 'Lavado Completo de Ropa')->first()->id,
            'unidad' => 'KILO',
            'peso_kg' => 5.00,
            'cantidad' => null,
            'precio_unitario' => 13.00,
            'descuento' => 0.00,
            'fragancia' => 'Lavanda',
            'notas' => null,
            'subtotal' => 65.00,
            'total_linea' => 65.00
        ]);

        // Orden 2: María García - 2 Edredones con promoción (CREDITO, ENTREGADA)
        $orden2 = Orden::create([
            'nro' => 'BEL-000002',
            'fecha_recepcion' => Carbon::parse('2024-09-05'),
            'fecha_listo' => Carbon::parse('2024-09-07'),
            'fecha_entrega' => Carbon::parse('2024-09-07'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CREDITO',
            'fecha_vencimiento' => Carbon::parse('2024-10-05'),
            'subtotal' => 140.00,
            'descuento' => 40.00, // 28.57% de descuento
            'total' => 100.00,
            'cliente_id' => 2,
            'usuario_id' => 3, // Usuario empleado
            'observaciones' => 'Promo Edredones de Temporada aplicada'
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000002',
            'servicio_id' => $servicios->where('nombre', 'Edredón 2 Plazas')->first()->id,
            'unidad' => 'PIEZA',
            'peso_kg' => null,
            'cantidad' => 2,
            'precio_unitario' => 70.00,
            'descuento' => 40.00,
            'fragancia' => null,
            'notas' => 'Aplicar promoción de temporada',
            'subtotal' => 140.00,
            'total_linea' => 100.00
        ]);

        // Orden 3: Carlos Mendoza - Lavado de ropa 8kg + Planchado 5 piezas (CONTADO, ENTREGADA)
        $orden3 = Orden::create([
            'nro' => 'BEL-000003',
            'fecha_recepcion' => Carbon::parse('2024-09-12'),
            'fecha_listo' => Carbon::parse('2024-09-13'),
            'fecha_entrega' => Carbon::parse('2024-09-13'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CONTADO',
            'fecha_vencimiento' => null,
            'subtotal' => 154.00,
            'descuento' => 0.00,
            'total' => 154.00,
            'cliente_id' => 3,
            'usuario_id' => 2, // Usuario empleado
            'observaciones' => null
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000003',
            'servicio_id' => $servicios->where('nombre', 'Lavado Completo de Ropa')->first()->id,
            'unidad' => 'KILO',
            'peso_kg' => 8.00,
            'cantidad' => null,
            'precio_unitario' => 13.00,
            'descuento' => 0.00,
            'fragancia' => 'Floral',
            'notas' => null,
            'subtotal' => 104.00,
            'total_linea' => 104.00
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000003',
            'servicio_id' => $servicios->where('nombre', 'Planchado')->first()->id,
            'unidad' => 'PIEZA',
            'peso_kg' => null,
            'cantidad' => 5,
            'precio_unitario' => 10.00,
            'descuento' => 0.00,
            'fragancia' => null,
            'notas' => 'Camisas y pantalones formales',
            'subtotal' => 50.00,
            'total_linea' => 50.00
        ]);

        // Orden 4: Ana López - Par de zapatos (CONTADO, ENTREGADA)
        $orden4 = Orden::create([
            'nro' => 'BEL-000004',
            'fecha_recepcion' => Carbon::parse('2024-09-18'),
            'fecha_listo' => Carbon::parse('2024-09-19'),
            'fecha_entrega' => Carbon::parse('2024-09-19'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CONTADO',
            'fecha_vencimiento' => null,
            'subtotal' => 25.00,
            'descuento' => 0.00,
            'total' => 25.00,
            'cliente_id' => 4,
            'usuario_id' => 3, // Usuario empleado
            'observaciones' => 'Zapatos deportivos blancos'
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000004',
            'servicio_id' => $servicios->where('nombre', 'Lavado de Zapatos (Par)')->first()->id,
            'unidad' => 'PIEZA',
            'peso_kg' => null,
            'cantidad' => 1,
            'precio_unitario' => 25.00,
            'descuento' => 0.00,
            'fragancia' => null,
            'notas' => null,
            'subtotal' => 25.00,
            'total_linea' => 25.00
        ]);

        // Orden 5: Roberto Paz - Colcha Grande + Juego de Sábanas (QR, ENTREGADA)
        $orden5 = Orden::create([
            'nro' => 'BEL-000005',
            'fecha_recepcion' => Carbon::parse('2024-09-25'),
            'fecha_listo' => Carbon::parse('2024-09-27'),
            'fecha_entrega' => Carbon::parse('2024-09-27'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CONTADO',
            'fecha_vencimiento' => null,
            'subtotal' => 115.00,
            'descuento' => 0.00,
            'total' => 115.00,
            'cliente_id' => 5,
            'usuario_id' => 2, // Usuario empleado
            'observaciones' => 'Pago con QR'
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000005',
            'servicio_id' => $servicios->where('nombre', 'Colcha Grande')->first()->id,
            'unidad' => 'PIEZA',
            'peso_kg' => null,
            'cantidad' => 1,
            'precio_unitario' => 80.00,
            'descuento' => 0.00,
            'fragancia' => null,
            'notas' => null,
            'subtotal' => 80.00,
            'total_linea' => 80.00
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000005',
            'servicio_id' => $servicios->where('nombre', 'Juego de Sábanas')->first()->id,
            'unidad' => 'PIEZA',
            'peso_kg' => null,
            'cantidad' => 1,
            'precio_unitario' => 35.00,
            'descuento' => 0.00,
            'fragancia' => null,
            'notas' => null,
            'subtotal' => 35.00,
            'total_linea' => 35.00
        ]);

        // Orden 6: Laura Sánchez - 3kg ropa (CONTADO, LISTA)
        $orden6 = Orden::create([
            'nro' => 'BEL-000006',
            'fecha_recepcion' => Carbon::parse('2024-10-02'),
            'fecha_listo' => Carbon::parse('2024-10-03'),
            'fecha_entrega' => null,
            'estado' => 'LISTA',
            'forma_pago' => 'CONTADO',
            'fecha_vencimiento' => null,
            'subtotal' => 39.00,
            'descuento' => 0.00,
            'total' => 39.00,
            'cliente_id' => 6,
            'usuario_id' => 3, // Usuario empleado
            'observaciones' => 'Lista para recoger'
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000006',
            'servicio_id' => $servicios->where('nombre', 'Lavado Completo de Ropa')->first()->id,
            'unidad' => 'KILO',
            'peso_kg' => 3.00,
            'cantidad' => null,
            'precio_unitario' => 13.00,
            'descuento' => 0.00,
            'fragancia' => 'Cítrica',
            'notas' => null,
            'subtotal' => 39.00,
            'total_linea' => 39.00
        ]);

        // Orden 7: Jorge Ramírez - 2 Colchas Delgadas con promo (CREDITO, ENTREGADA, pago parcial)
        $orden7 = Orden::create([
            'nro' => 'BEL-000007',
            'fecha_recepcion' => Carbon::parse('2024-10-08'),
            'fecha_listo' => Carbon::parse('2024-10-10'),
            'fecha_entrega' => Carbon::parse('2024-10-10'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CREDITO',
            'fecha_vencimiento' => Carbon::parse('2024-11-08'),
            'subtotal' => 100.00,
            'descuento' => 20.00, // 20% descuento
            'total' => 80.00,
            'cliente_id' => 7,
            'usuario_id' => 2, // Usuario empleado
            'observaciones' => 'Promo Colchas - Cliente pagará en 2 cuotas'
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000007',
            'servicio_id' => $servicios->where('nombre', 'Colcha Delgada')->first()->id,
            'unidad' => 'PIEZA',
            'peso_kg' => null,
            'cantidad' => 2,
            'precio_unitario' => 50.00,
            'descuento' => 20.00,
            'fragancia' => null,
            'notas' => 'Aplicar promoción especial',
            'subtotal' => 100.00,
            'total_linea' => 80.00
        ]);

        // Orden 8-20: Más órdenes variadas
        $this->crearOrdenesAdicionales($servicios);
    }

    private function crearOrdenesAdicionales($servicios)
    {
        // Orden 8
        Orden::create([
            'nro' => 'BEL-000008',
            'fecha_recepcion' => Carbon::parse('2024-10-15'),
            'fecha_listo' => Carbon::parse('2024-10-16'),
            'fecha_entrega' => Carbon::parse('2024-10-16'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CONTADO',
            'fecha_vencimiento' => null,
            'subtotal' => 91.00,
            'descuento' => 0.00,
            'total' => 91.00,
            'cliente_id' => 8,
            'usuario_id' => 3, // Usuario empleado
            'observaciones' => null
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000008',
            'servicio_id' => $servicios->where('nombre', 'Lavado Completo de Ropa')->first()->id,
            'unidad' => 'KILO',
            'peso_kg' => 7.00,
            'cantidad' => null,
            'precio_unitario' => 13.00,
            'descuento' => 0.00,
            'fragancia' => 'Lavanda',
            'notas' => null,
            'subtotal' => 91.00,
            'total_linea' => 91.00
        ]);

        // Orden 9
        Orden::create([
            'nro' => 'BEL-000009',
            'fecha_recepcion' => Carbon::parse('2024-10-18'),
            'fecha_listo' => Carbon::parse('2024-10-20'),
            'fecha_entrega' => Carbon::parse('2024-10-20'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CONTADO',
            'fecha_vencimiento' => null,
            'subtotal' => 70.00,
            'descuento' => 0.00,
            'total' => 70.00,
            'cliente_id' => 9,
            'usuario_id' => 2, // Usuario empleado
            'observaciones' => null
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000009',
            'servicio_id' => $servicios->where('nombre', 'Edredón 2 Plazas')->first()->id,
            'unidad' => 'PIEZA',
            'peso_kg' => null,
            'cantidad' => 1,
            'precio_unitario' => 70.00,
            'descuento' => 0.00,
            'fragancia' => null,
            'notas' => null,
            'subtotal' => 70.00,
            'total_linea' => 70.00
        ]);

        // Orden 10
        Orden::create([
            'nro' => 'BEL-000010',
            'fecha_recepcion' => Carbon::parse('2024-10-22'),
            'fecha_listo' => Carbon::parse('2024-10-23'),
            'fecha_entrega' => Carbon::parse('2024-10-23'),
            'estado' => 'ENTREGADA',
            'forma_pago' => 'CONTADO',
            'fecha_vencimiento' => null,
            'subtotal' => 40.00,
            'descuento' => 0.00,
            'total' => 40.00,
            'cliente_id' => 10,
            'usuario_id' => 3, // Usuario empleado
            'observaciones' => null
        ]);

        OrdenDetalle::create([
            'orden_nro' => 'BEL-000010',
            'servicio_id' => $servicios->where('nombre', 'Alfombra Pequeña')->first()->id,
            'unidad' => 'PIEZA',
            'peso_kg' => null,
            'cantidad' => 1,
            'precio_unitario' => 40.00,
            'descuento' => 0.00,
            'fragancia' => null,
            'notas' => 'Alfombra de baño',
            'subtotal' => 40.00,
            'total_linea' => 40.00
        ]);

        // Órdenes 11-20 (adicionales rápidas)
        for ($i = 11; $i <= 20; $i++) {
            $clienteId = (($i - 1) % 10) + 1;
            $usuarioId = (($i % 2) == 0) ? 2 : 3;
            $fechaRecepcion = Carbon::parse('2024-11-01')->addDays($i - 11);
            
            $esEntregada = $i <= 17;
            $estado = $esEntregada ? 'ENTREGADA' : ($i == 18 ? 'LISTA' : 'PENDIENTE');
            $fechaListo = $esEntregada || $i == 18 ? $fechaRecepcion->copy()->addDay() : null;
            $fechaEntrega = $esEntregada ? $fechaListo : null;

            $pesoKg = rand(3, 10);
            $total = $pesoKg * 13;

            Orden::create([
                'nro' => sprintf('BEL-%06d', $i),
                'fecha_recepcion' => $fechaRecepcion,
                'fecha_listo' => $fechaListo,
                'fecha_entrega' => $fechaEntrega,
                'estado' => $estado,
                'forma_pago' => 'CONTADO',
                'fecha_vencimiento' => null,
                'subtotal' => $total,
                'descuento' => 0.00,
                'total' => $total,
                'cliente_id' => $clienteId,
                'usuario_id' => $usuarioId,
                'observaciones' => null
            ]);

            OrdenDetalle::create([
                'orden_nro' => sprintf('BEL-%06d', $i),
                'servicio_id' => $servicios->where('nombre', 'Lavado Completo de Ropa')->first()->id,
                'unidad' => 'KILO',
                'peso_kg' => $pesoKg,
                'cantidad' => null,
                'precio_unitario' => 13.00,
                'descuento' => 0.00,
                'fragancia' => 'Lavanda',
                'notas' => null,
                'subtotal' => $total,
                'total_linea' => $total
            ]);
        }
    }
}
