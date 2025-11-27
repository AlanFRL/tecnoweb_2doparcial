<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('id', 'ASC')->get();

        return Inertia::render('Usuarios/Clientes/Index', [
            'clientes' => $clientes,
        ]);
    }

    public function create()
    {
        return Inertia::render('Usuarios/Clientes/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:50',
            'direccion' => 'nullable|string',
            'telefono'  => 'nullable|string|max:20',
        ]);

        Cliente::create($validated);

        return redirect()
            ->route('usuarios.clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        return Inertia::render('Usuarios/Clientes/Edit', [
            'cliente' => $cliente,
        ]);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre'    => 'required|string|max:50',
            'direccion' => 'nullable|string',
            'telefono'  => 'nullable|string|max:20',
        ]);

        $cliente->update($validated);

        return redirect()
            ->route('usuarios.clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()
            ->route('usuarios.clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
