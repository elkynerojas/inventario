@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Sistema de Inventario')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-people"></i> Gestión de Usuarios</h2>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Usuario
                    </a>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filtros y búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('usuarios.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Buscar por nombre, email o documento..."
                               class="form-control">
                    </div>
                    <div class="col-md-4">
                        <select name="rol_id" class="form-select">
                            <option value="">Todos los roles</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}" {{ request('rol_id') == $rol->id ? 'selected' : '' }}>
                                    {{ ucfirst($rol->nombre) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    @if(request()->hasAny(['search', 'rol_id']))
                        <div class="col-12">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Limpiar Filtros
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Documento</th>
                                    <th>Rol</th>
                                    <th>Activos Asignados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->documento }}</td>
                                        <td>
                                            @if($user->rol)
                                                <span class="badge 
                                                    {{ $user->rol->nombre === 'admin' ? 'bg-danger' : 
                                                       ($user->rol->nombre === 'profesor' ? 'bg-primary' : 'bg-success') }}">
                                                    {{ ucfirst($user->rol->nombre) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Sin rol</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge 
                                                {{ $user->cantidadActivosAsignados() > 0 ? 'bg-info' : 'bg-secondary' }}">
                                                {{ $user->cantidadActivosAsignados() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('usuarios.show', $user) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('usuarios.edit', $user) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('usuarios.reset-password', $user) }}" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   title="Restablecer contraseña">
                                                    <i class="bi bi-key"></i>
                                                </a>
                                                <form method="POST" 
                                                      action="{{ route('usuarios.destroy', $user) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No se encontraron usuarios</h4>
                        <p class="text-muted mb-4">
                            @if(request()->hasAny(['search', 'rol_id']))
                                No hay usuarios que coincidan con los filtros aplicados.
                            @else
                                Aún no hay usuarios registrados en el sistema.
                            @endif
                        </p>
                        @if(!request()->hasAny(['search', 'rol_id']))
                            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Crear primer usuario
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
