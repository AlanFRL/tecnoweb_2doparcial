<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InsumoController extends Controller
{
    public function index()
    {
        $insumos = Insumo::orderBy('codigo')->get();

        // Movimientos recientes (solo para mostrar en la misma sección)
        $movimientosRecientes = Inventario::with('insumo')
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        return Inertia::render('Insumos/Index', [
            'insumos'   => $insumos,
            'movimientos' => $movimientosRecientes,
        ]);
    }

    public function create()
    {
        return Inertia::render('Insumos/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo'         => 'required|string|max:20|unique:insumo,codigo',
            'nombre'         => 'required|string|max:255',
            'cantidad'       => 'required|numeric|min:0',
            'unidad_medida'  => 'required|string|max:10',
            'stock'          => 'required|numeric|min:0',
            'stock_min'      => 'required|numeric|min:0',
            'stock_max'      => 'required|numeric|min:0',
            'costo_promedio' => 'required|numeric|min:0',
            'estado'         => 'required|boolean',
        ]);

        Insumo::create($data);

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo creado correctamente.');
    }

    public function edit(Insumo $insumo)
    {
        return Inertia::render('Insumos/Edit', [
            'insumo' => $insumo,
        ]);
    }

    public function update(Request $request, Insumo $insumo)
    {
        $data = $request->validate([
            'codigo'         => 'required|string|max:20|unique:insumo,codigo,' . $insumo->codigo . ',codigo',
            'nombre'         => 'required|string|max:255',
            'cantidad'       => 'required|numeric|min:0',
            'unidad_medida'  => 'required|string|max:10',
            'stock'          => 'required|numeric|min:0',
            'stock_min'      => 'required|numeric|min:0',
            'stock_max'      => 'required|numeric|min:0',
            'costo_promedio' => 'required|numeric|min:0',
            'estado'         => 'required|boolean',
        ]);

        $insumo->update($data);

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo actualizado correctamente.');
    }

    public function destroy(Insumo $insumo)
    {
        $insumo->delete();

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo eliminado correctamente.');
    }
}
