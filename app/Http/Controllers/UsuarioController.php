<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    /**
     * Mostrar lista de empleados
     */
    public function index()
    {
        return Inertia::render('Usuarios/Empleados/Index', [
            'usuarios' => Usuario::where('tipo_usuario', 'empleado')
                ->orderBy('id', 'ASC')
                ->get()
        ]);
    }

    /**
     * Formulario crear empleado
     */
    public function create()
    {
        return Inertia::render('Usuarios/Empleados/Create');
    }

    /**
     * Guardar empleado
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'telefono' => 'nullable',
            'email' => 'required|email|unique:usuario,email',
            'password' => 'required|min:6',
            'tipo_usuario' => 'required|in:empleado' // SOLO empleados
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tipo_usuario' => 'empleado',
            'estado' => true
        ]);

        return redirect()->route('usuarios.empleados.index');
    }

    /**
     * Editar empleado
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        return Inertia::render('Usuarios/Empleados/Edit', [
            'usuario' => $usuario
        ]);
    }

    /**
     * Actualizar empleado
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'telefono' => 'nullable',
            'email' => 'required|email|unique:usuario,email,' . $usuario->id,
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('usuarios.empleados.index');
    }

    /**
     * Eliminar empleado
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.empleados.index');
    }
}
