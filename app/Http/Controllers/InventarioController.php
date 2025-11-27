<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventarioController extends Controller
{
    // Lista de movimientos para un insumo
    public function index(Insumo $insumo)
    {
        $movimientos = $insumo->movimientos()
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Insumos/Inventario/Index', [
            'insumo'      => $insumo,
            'movimientos' => $movimientos,
        ]);
    }

    public function create(Insumo $insumo)
    {
        return Inertia::render('Insumos/Inventario/Create', [
            'insumo' => $insumo,
        ]);
    }

    public function store(Request $request, Insumo $insumo)
    {
        $data = $request->validate([
            'tipo'          => 'required|string|max:20',  // ENTRADA / SALIDA
            'fecha'         => 'required|date',
            'cantidad'      => 'required|numeric|min:0',
            'costo_unitario'=> 'required|numeric|min:0',
            'referencia'    => 'nullable|string|max:255',
            'usuario_id'    => 'nullable|integer',
            'proveedor_id'  => 'nullable|integer',
        ]);

        $data['insumo_codigo'] = $insumo->codigo;

        Inventario::create($data);

        // (Opcional) Actualizar stock del insumo aquí si quieres

        return redirect()
            ->route('insumos.inventario.index', $insumo->codigo)
            ->with('success', 'Movimiento registrado.');
    }

    public function edit(Insumo $insumo, Inventario $movimiento)
    {
        return Inertia::render('Insumos/Inventario/Edit', [
            'insumo'     => $insumo,
            'movimiento' => $movimiento,
        ]);
    }

    public function update(Request $request, Insumo $insumo, Inventario $movimiento)
    {
        $data = $request->validate([
            'tipo'          => 'required|string|max:20',
            'fecha'         => 'required|date',
            'cantidad'      => 'required|numeric|min:0',
            'costo_unitario'=> 'required|numeric|min:0',
            'referencia'    => 'nullable|string|max:255',
            'usuario_id'    => 'nullable|integer',
            'proveedor_id'  => 'nullable|integer',
        ]);

        $movimiento->update($data);

        return redirect()
            ->route('insumos.inventario.index', $insumo->codigo)
            ->with('success', 'Movimiento actualizado.');
    }

    public function destroy(Insumo $insumo, Inventario $movimiento)
    {
        $movimiento->delete();

        return redirect()
            ->route('insumos.inventario.index', $insumo->codigo)
            ->with('success', 'Movimiento eliminado.');
    }
}
