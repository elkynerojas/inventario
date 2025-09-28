@extends('layouts.app')

@section('title', 'Gestión de Roles - Sistema de Inventario')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-person-badge"></i> Gestión de Roles</h2>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Rol
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

        <!-- Tabla de roles -->
        <div class="card">
            <div class="card-body">
                @if($roles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Rol</th>
                                    <th>Usuarios Asignados</th>
                                    <th>Fecha de Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $rol)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center me-3
                                                    {{ $rol->nombre === 'admin' ? 'bg-danger' : 
                                                       ($rol->nombre === 'profesor' ? 'bg-primary' : 'bg-success') }}">
                                                    <span class="text-white fw-bold">
                                                        {{ strtoupper(substr($rol->nombre, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ ucfirst($rol->nombre) }}</div>
                                                    @if($rol->nombre === 'admin')
                                                        <small class="text-danger">
                                                            <i class="bi bi-crown me-1"></i>Administrador
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $rol->usuarios_count > 0 ? 'bg-info' : 'bg-secondary' }}">
                                                {{ $rol->usuarios_count }} usuario{{ $rol->usuarios_count !== 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td>{{ $rol->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('roles.show', ['role' => $rol->id]) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('roles.edit', ['role' => $rol->id]) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if($rol->nombre !== 'admin')
                                                    <form method="POST" 
                                                          action="{{ route('roles.destroy', ['role' => $rol->id]) }}" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Está seguro de eliminar este rol? Esta acción no se puede deshacer.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-person-badge text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No hay roles registrados</h4>
                        <p class="text-muted mb-4">
                            Aún no hay roles creados en el sistema.
                        </p>
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear primer rol
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection