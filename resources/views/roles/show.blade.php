@extends('layouts.app')

@section('title', 'Detalles del Rol - Sistema de Inventario')

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
    <div class="col-md-10">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-person-badge"></i> Detalles del Rol</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('roles.edit', ['role' => $rol->id]) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información principal -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="bi bi-person-badge fs-2"></i>
                            </div>
                            <div>
                                <h3 class="mb-1">{{ ucfirst($rol->nombre) }}</h3>
                                <p class="text-muted mb-0">Rol del Sistema</p>
                            </div>
                        </div>
                        
                        <!-- Estado y información -->
                        <div class="d-flex gap-3 mb-3">
                            <span class="badge bg-primary fs-6">
                                {{ ucfirst($rol->nombre) }}
                            </span>
                            <span class="badge bg-info fs-6">
                                {{ $rol->usuarios->count() }} usuarios asignados
                            </span>
                        </div>
                    </div>
                    
                    <!-- Acciones rápidas -->
                    <div class="col-md-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('roles.edit', ['role' => $rol->id]) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Editar Rol
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información detallada -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Sistema</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">ID del Rol:</dt>
                    <dd class="col-sm-9">{{ $rol->id }}</dd>
                    
                    <dt class="col-sm-3">Nombre:</dt>
                    <dd class="col-sm-9">{{ ucfirst($rol->nombre) }}</dd>
                    
                    <dt class="col-sm-3">Fecha de Creación:</dt>
                    <dd class="col-sm-9">{{ $rol->created_at->format('d/m/Y H:i') }}</dd>
                    
                    <dt class="col-sm-3">Última Actualización:</dt>
                    <dd class="col-sm-9">{{ $rol->updated_at->format('d/m/Y H:i') }}</dd>
                    
                    <dt class="col-sm-3">Usuarios Asignados:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-info fs-6">
                            {{ $rol->usuarios->count() }} usuarios
                        </span>
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Usuarios asignados -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people"></i> Usuarios Asignados ({{ $rol->usuarios->count() }})</h5>
            </div>
            <div class="card-body">
                @if($rol->usuarios->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Documento</th>
                                    <th>Fecha de Registro</th>
                                    <th>Acciones</th>
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
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('usuarios.show', $usuario) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('usuarios.edit', $usuario) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-person-x display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">No hay usuarios asignados</h5>
                        <p class="text-muted">Este rol no tiene usuarios asignados actualmente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

.avatar-lg {
    width: 64px;
    height: 64px;
    font-size: 24px;
}
</style>
@endif
@endsection
