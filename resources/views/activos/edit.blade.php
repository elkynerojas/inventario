@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Editar Activo</h4>
                    <a href="{{ route('activos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    @if($activoDadoDeBaja)
                        <div class="alert alert-warning">
                            <h5><i class="bi bi-exclamation-triangle"></i> Activo Dado de Baja</h5>
                            <p class="mb-0">
                                Este activo ha sido dado de baja y no puede ser editado. 
                                Todos los campos están deshabilitados para mantener la integridad de los datos históricos.
                            </p>
                        </div>
                    @endif
                    
                    <form action="{{ route('activos.update', $activo) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Información Básica -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Información Básica</h5>
                                
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código *</label>
                                    <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                           id="codigo" name="codigo" value="{{ old('codigo', $activo->codigo) }}" 
                                           {{ $activoDadoDeBaja ? 'disabled' : 'required' }}>
                                    @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" name="nombre" value="{{ old('nombre', $activo->nombre) }}" 
                                           {{ $activoDadoDeBaja ? 'disabled' : 'required' }}>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado *</label>
                                    <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" 
                                            {{ $activoDadoDeBaja ? 'disabled' : 'required' }}>
                                        <option value="">Seleccionar estado</option>
                                        <option value="bueno" {{ old('estado', $activo->estado) == 'bueno' ? 'selected' : '' }}>Bueno</option>
                                        <option value="regular" {{ old('estado', $activo->estado) == 'regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="malo" {{ old('estado', $activo->estado) == 'malo' ? 'selected' : '' }}>Malo</option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tipo_bien" class="form-label">Tipo de Bien</label>
                                    <input type="text" class="form-control @error('tipo_bien') is-invalid @enderror" 
                                           id="tipo_bien" name="tipo_bien" value="{{ old('tipo_bien', $activo->tipo_bien) }}">
                                    @error('tipo_bien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Información Técnica -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Información Técnica</h5>
                                
                                <div class="mb-3">
                                    <label for="marca" class="form-label">Marca</label>
                                    <input type="text" class="form-control @error('marca') is-invalid @enderror" 
                                           id="marca" name="marca" value="{{ old('marca', $activo->marca) }}">
                                    @error('marca')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="modelo" class="form-label">Modelo</label>
                                    <input type="text" class="form-control @error('modelo') is-invalid @enderror" 
                                           id="modelo" name="modelo" value="{{ old('modelo', $activo->modelo) }}">
                                    @error('modelo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="serial" class="form-label">Serial</label>
                                    <input type="text" class="form-control @error('serial') is-invalid @enderror" 
                                           id="serial" name="serial" value="{{ old('serial', $activo->serial) }}">
                                    @error('serial')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="ubicacion" class="form-label">Ubicación</label>
                                    <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" 
                                           id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $activo->ubicacion) }}">
                                    @error('ubicacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Información Financiera -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Información Financiera</h5>
                                
                                <div class="mb-3">
                                    <label for="valor_compra" class="form-label">Valor de Compra</label>
                                    <input type="number" step="0.01" class="form-control @error('valor_compra') is-invalid @enderror" 
                                           id="valor_compra" name="valor_compra" value="{{ old('valor_compra', $activo->valor_compra) }}">
                                    @error('valor_compra')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="fecha_compra" class="form-label">Fecha de Compra</label>
                                    <input type="date" class="form-control @error('fecha_compra') is-invalid @enderror" 
                                           id="fecha_compra" name="fecha_compra" value="{{ old('fecha_compra', $activo->fecha_compra) }}">
                                    @error('fecha_compra')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nro_compra" class="form-label">Número de Compra</label>
                                    <input type="text" class="form-control @error('nro_compra') is-invalid @enderror" 
                                           id="nro_compra" name="nro_compra" value="{{ old('nro_compra', $activo->nro_compra) }}">
                                    @error('nro_compra')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Información de Responsabilidad -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Responsabilidad</h5>
                                
                                <div class="mb-3">
                                    <label for="codigo_responsable" class="form-label">Código Responsable</label>
                                    <input type="text" class="form-control @error('codigo_responsable') is-invalid @enderror" 
                                           id="codigo_responsable" name="codigo_responsable" value="{{ old('codigo_responsable', $activo->codigo_responsable) }}">
                                    @error('codigo_responsable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nombre_responsable" class="form-label">Nombre Responsable</label>
                                    <input type="text" class="form-control @error('nombre_responsable') is-invalid @enderror" 
                                           id="nombre_responsable" name="nombre_responsable" value="{{ old('nombre_responsable', $activo->nombre_responsable) }}">
                                    @error('nombre_responsable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="codigo_servicio" class="form-label">Código de Servicio</label>
                                    <input type="text" class="form-control @error('codigo_servicio') is-invalid @enderror" 
                                           id="codigo_servicio" name="codigo_servicio" value="{{ old('codigo_servicio', $activo->codigo_servicio) }}">
                                    @error('codigo_servicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Observaciones -->
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Observaciones</h5>
                                
                                <div class="mb-3">
                                    <label for="observacion" class="form-label">Observación</label>
                                    <textarea class="form-control @error('observacion') is-invalid @enderror" 
                                              id="observacion" name="observacion" rows="3">{{ old('observacion', $activo->observacion) }}</textarea>
                                    @error('observacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $activo->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('activos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary" {{ $activoDadoDeBaja ? 'disabled' : '' }}>
                                <i class="fas fa-save"></i> {{ $activoDadoDeBaja ? 'Edición Deshabilitada' : 'Actualizar Activo' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($activoDadoDeBaja)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Deshabilitar todos los campos del formulario
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(function(input) {
        input.disabled = true;
    });
    
    // Deshabilitar el botón de envío
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
    }
});
</script>
@endif
@endsection
