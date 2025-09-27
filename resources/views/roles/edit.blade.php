@extends('layouts.app')

@section('title', 'Editar Rol - Sistema de Inventario')

@section('content')
@if(!isset($rol) || !$rol || !$rol->id)
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Error: No se pudo cargar la información del rol.
    </div>
    <div class="text-center">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver a Roles
        </a>
    </div>
@else
<div class="row justify-content-center">
    <div class="col-md-8">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="mb-0"><i class="bi bi-person-badge"></i> Editar Rol</h2>
            </div>
        </div>

        <!-- Formulario de edición -->
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('roles.update', ['role' => $rol->id]) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nombre del rol -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre del Rol</label>
                        <input type="text" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre', $rol->nombre) }}" 
                               required 
                               autofocus
                               placeholder="Ingresa el nombre del rol">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Información del rol actual -->
                    <div class="mb-4">
                        <label class="form-label">Información del Rol</label>
                        <div class="d-flex gap-3">
                            <span class="badge bg-primary fs-6">
                                {{ ucfirst($rol->nombre) }}
                            </span>
                            <span class="badge bg-info fs-6">
                                {{ $rol->usuarios->count() }} usuarios asignados
                            </span>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h5 class="text-muted mb-2">Información Adicional</h5>
                        <div class="row text-sm">
                            <div class="col-md-6">
                                <strong>ID del Rol:</strong> {{ $rol->id }}
                            </div>
                            <div class="col-md-6">
                                <strong>Creado:</strong> {{ $rol->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Última Actualización:</strong> {{ $rol->updated_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Usuarios Asignados:</strong> {{ $rol->usuarios->count() }}
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Actualizar Rol
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Usuarios asignados al rol -->
        @if($rol->usuarios->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people"></i> Usuarios Asignados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Documento</th>
                                <th>Fecha de Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rol->usuarios as $usuario)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ substr($usuario->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $usuario->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->documento }}</td>
                                <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}
</style>
@endif
@endsection
