@extends('layouts.app')

@section('title', 'Nueva Asignación de Activo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-plus"></i> Nueva Asignación de Activo
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('asignaciones.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="activo_id" class="form-label">Activo <span class="text-danger">*</span></label>
                            <select name="activo_id" id="activo_id" class="form-select @error('activo_id') is-invalid @enderror" required>
                                <option value="">Seleccione un activo</option>
                                @foreach($activosDisponibles as $activo)
                                    <option value="{{ $activo->id }}" {{ old('activo_id') == $activo->id ? 'selected' : '' }}>
                                        {{ $activo->codigo }} - {{ $activo->nombre }}
                                        @if($activo->marca || $activo->modelo)
                                            ({{ $activo->marca }} {{ $activo->modelo }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('activo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
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
                                   value="{{ old('fecha_asignacion', now()->toDateString()) }}" required>
                            @error('fecha_asignacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ubicacion_asignada" class="form-label">Ubicación Asignada</label>
                            <input type="text" name="ubicacion_asignada" id="ubicacion_asignada" 
                                   class="form-control @error('ubicacion_asignada') is-invalid @enderror" 
                                   value="{{ old('ubicacion_asignada') }}" 
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
                                  placeholder="Observaciones adicionales sobre la asignación...">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Asignar Activo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Establecer fecha mínima como hoy
    document.getElementById('fecha_asignacion').min = new Date().toISOString().split('T')[0];
</script>
@endpush
@endsection
