<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Mostrar la lista de usuarios
     */
    public function index(Request $request)
    {
        $query = User::with('rol');

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('rol_id')) {
            $query->where('rol_id', $request->rol_id);
        }

        $users = $query->orderBy('name')->paginate(15);
        $roles = Rol::orderBy('nombre')->get();

        return view('usuarios.index', compact('users', 'roles'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario
     */
    public function create()
    {
        $roles = Rol::orderBy('nombre')->get();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Almacenar un nuevo usuario
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'documento' => $request->documento,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar los detalles de un usuario específico
     */
    public function show(User $usuario)
    {
        $usuario->load('rol', 'asignacionesActivos.activo');
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Mostrar el formulario para editar un usuario
     */
    public function edit(User $usuario)
    {
        $roles = Rol::orderBy('nombre')->get();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Actualizar un usuario
     */
    public function update(UpdateUserRequest $request, User $usuario)
    {
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'documento' => $request->documento,
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(User $usuario)
    {
        // Verificar si el usuario tiene asignaciones activas
        if ($usuario->tieneActivosAsignados()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No se puede eliminar el usuario porque tiene activos asignados.');
        }

        // Verificar si es el último administrador
        if ($usuario->esAdmin()) {
            $adminCount = User::whereHas('rol', function ($query) {
                $query->where('nombre', 'admin');
            })->count();

            if ($adminCount <= 1) {
                return redirect()->route('usuarios.index')
                    ->with('error', 'No se puede eliminar el último administrador del sistema.');
            }
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Mostrar formulario para restablecer contraseña
     */
    public function showResetPassword(User $usuario)
    {
        return view('usuarios.reset-password', compact('usuario'));
    }

    /**
     * Restablecer contraseña de usuario
     */
    public function resetPassword(ResetPasswordRequest $request, User $usuario)
    {
        $usuario->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Contraseña restablecida exitosamente.');
    }
}
