@extends('layouts.app')

@section('title', 'Editar Asignación')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Editar Asignación #{{ $asignacion->id }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('asignaciones.update', $asignacion) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="activo_info" class="form-label">Activo</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                <strong>{{ $asignacion->activo->codigo }}</strong> - {{ $asignacion->activo->nombre }}
                                @if($asignacion->activo->marca || $asignacion->activo->modelo)
                                    <br><small class="text-muted">{{ $asignacion->activo->marca }} {{ $asignacion->activo->modelo }}</small>
                                @endif
                            </div>
                            <small class="text-muted">El activo no se puede cambiar en una asignación activa.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('user_id', $asignacion->user_id) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_asignacion" class="form-label">Fecha de Asignación <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_asignacion" id="fecha_asignacion" 
                                   class="form-control @error('fecha_asignacion') is-invalid @enderror" 
                                   value="{{ old('fecha_asignacion', $asignacion->fecha_asignacion->toDateString()) }}" required>
                            @error('fecha_asignacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ubicacion_asignada" class="form-label">Ubicación Asignada</label>
                            <input type="text" name="ubicacion_asignada" id="ubicacion_asignada" 
                                   class="form-control @error('ubicacion_asignada') is-invalid @enderror" 
                                   value="{{ old('ubicacion_asignada', $asignacion->ubicacion_asignada) }}" 
                                   placeholder="Ej: Oficina 101, Laboratorio A">
                            @error('ubicacion_asignada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3" 
                                  class="form-control @error('observaciones') is-invalid @enderror" 
                                  placeholder="Observaciones adicionales sobre la asignación...">{{ old('observaciones', $asignacion->observaciones) }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Información adicional -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle"></i> Información de la Asignación
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Asignado por:</strong> {{ $asignacion->asignadoPor->name }}<br>
                                <strong>Estado actual:</strong> 
                                @if($asignacion->estaActiva())
                                    <span class="badge bg-success">Activa</span>
                                @elseif($asignacion->fueDevuelta())
                                    <span class="badge bg-info">Devuelta</span>
                                @elseif($asignacion->estaPerdida())
                                    <span class="badge bg-warning">Perdida</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha de creación:</strong> {{ $asignacion->created_at->format('d/m/Y H:i') }}<br>
                                @if($asignacion->estaActiva())
                                    <strong>Duración:</strong> {{ $asignacion->duracion_en_dias }} días
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('asignaciones.show', $asignacion) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Actualizar Asignación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Establecer fecha mínima como fecha de asignación original
    document.getElementById('fecha_asignacion').min = '{{ $asignacion->fecha_asignacion->toDateString() }}';
</script>
@endpush
@endsection
