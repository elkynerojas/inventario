@extends('layouts.app')

@section('title', 'Detalles del Usuario - Sistema de Inventario')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-person-circle"></i> Detalles del Usuario - {{ $usuario->name }}</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información principal del usuario -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-4">
                                {{ strtoupper(substr($usuario->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="mb-1">{{ $usuario->name }}</h3>
                                <p class="text-muted mb-1">{{ $usuario->email }}</p>
                                <p class="text-muted mb-0">Documento: {{ $usuario->documento }}</p>
                            </div>
                        </div>
                        
                        <!-- Estado y rol -->
                        <div class="d-flex gap-3 mb-3">
                            @if($usuario->rol)
                                <span class="badge fs-6
                                    {{ $usuario->rol->nombre === 'admin' ? 'bg-danger' : 
                                       ($usuario->rol->nombre === 'profesor' ? 'bg-primary' : 'bg-success') }}">
                                    {{ ucfirst($usuario->rol->nombre) }}
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">Sin rol asignado</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Acciones rápidas -->
                    <div class="col-md-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('usuarios.reset-password', $usuario) }}" class="btn btn-warning">
                                <i class="bi bi-key"></i> Restablecer Contraseña
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información detallada -->
        <div class="row mb-4">
            <!-- Información del sistema -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">ID de Usuario:</dt>
                            <dd class="col-sm-8">{{ $usuario->id }}</dd>
                            
                            <dt class="col-sm-4">Fecha de Registro:</dt>
                            <dd class="col-sm-8">{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>
                            
                            <dt class="col-sm-4">Última Actualización:</dt>
                            <dd class="col-sm-8">{{ $usuario->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-6">Activos Asignados:</dt>
                            <dd class="col-sm-6">
                                <span class="badge {{ $usuario->cantidadActivosAsignados() > 0 ? 'bg-info' : 'bg-secondary' }}">
                                    {{ $usuario->cantidadActivosAsignados() }}
                                </span>
                            </dd>
                            
                            <dt class="col-sm-6">Total Asignaciones:</dt>
                            <dd class="col-sm-6">{{ $usuario->asignacionesActivos->count() }}</dd>
                            
                            <dt class="col-sm-6">Asignaciones Realizadas:</dt>
                            <dd class="col-sm-6">{{ $usuario->asignacionesRealizadas->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activos asignados -->
        @if($usuario->asignacionesActivos->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-laptop"></i> Activos Asignados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Activo</th>
                                    <th>Tipo</th>
                                    <th>Fecha de Asignación</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuario->asignacionesActivos as $asignacion)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $asignacion->activo->nombre }}</div>
                                            <small class="text-muted">Serial: {{ $asignacion->activo->serial }}</small>
                                        </td>
                                        <td>{{ $asignacion->activo->tipo }}</td>
                                        <td>{{ $asignacion->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @if($asignacion->estado === 'activa')
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($asignacion->estado) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-laptop text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">Sin activos asignados</h4>
                    <p class="text-muted">Este usuario no tiene activos asignados actualmente.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection