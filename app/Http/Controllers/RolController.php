<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolController extends Controller
{
    /**
     * Mostrar la lista de roles
     */
    public function index()
    {
        $roles = Rol::withCount('usuarios')->orderBy('nombre')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Mostrar el formulario para crear un nuevo rol
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Almacenar un nuevo rol
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|unique:roles,nombre',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique' => 'Este rol ya existe.',
            'nombre.max' => 'El nombre del rol no puede exceder los 50 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Rol::create([
            'nombre' => strtolower($request->nombre),
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Mostrar los detalles de un rol específico
     */
    public function show(Rol $rol)
    {
        $rol->load('usuarios');
        return view('roles.show', compact('rol'));
    }

    /**
     * Mostrar el formulario para editar un rol
     */
    public function edit(Rol $rol)
    {
        return view('roles.edit', compact('rol'));
    }

    /**
     * Actualizar un rol
     */
    public function update(Request $request, Rol $rol)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'string',
                'max:50',
                'unique:roles,nombre,' . $rol->id,
            ],
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique' => 'Este rol ya existe.',
            'nombre.max' => 'El nombre del rol no puede exceder los 50 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rol->update([
            'nombre' => strtolower($request->nombre),
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Eliminar un rol
     */
    public function destroy(Rol $rol)
    {
        // Verificar si el rol tiene usuarios asignados
        if ($rol->usuarios()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        // Verificar si es el rol de administrador
        if ($rol->nombre === 'admin') {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol de administrador.');
        }

        $rol->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }
}
