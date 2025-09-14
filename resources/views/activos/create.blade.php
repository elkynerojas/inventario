@extends('layouts.app')

@section('title', 'Crear Activo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Crear Nuevo Activo
                </h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('activos.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo" class="form-label">Código *</label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo" name="codigo" required value="{{ old('codigo') }}">
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" required value="{{ old('nombre') }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="">Seleccionar estado...</option>
                                <option value="bueno" {{ old('estado') === 'bueno' ? 'selected' : '' }}>Bueno</option>
                                <option value="regular" {{ old('estado') === 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="malo" {{ old('estado') === 'malo' ? 'selected' : '' }}>Malo</option>
                                <option value="dado de baja" {{ old('estado') === 'dado de baja' ? 'selected' : '' }}>Dado de Baja</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="valor_compra" class="form-label">Valor Compra *</label>
                            <input type="number" step="0.01" class="form-control @error('valor_compra') is-invalid @enderror" 
                                   id="valor_compra" name="valor_compra" required value="{{ old('valor_compra') }}">
                            @error('valor_compra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_compra" class="form-label">Fecha Compra</label>
                            <input type="date" class="form-control @error('fecha_compra') is-invalid @enderror" 
                                   id="fecha_compra" name="fecha_compra" value="{{ old('fecha_compra') }}">
                            @error('fecha_compra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control @error('marca') is-invalid @enderror" 
                                   id="marca" name="marca" value="{{ old('marca') }}">
                            @error('marca')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control @error('modelo') is-invalid @enderror" 
                                   id="modelo" name="modelo" value="{{ old('modelo') }}">
                            @error('modelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="serial" class="form-label">Serial</label>
                            <input type="text" class="form-control @error('serial') is-invalid @enderror" 
                                   id="serial" name="serial" value="{{ old('serial') }}">
                            @error('serial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" 
                                   id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}">
                            @error('ubicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre_responsable" class="form-label">Nombre Responsable</label>
                            <input type="text" class="form-control @error('nombre_responsable') is-invalid @enderror" 
                                   id="nombre_responsable" name="nombre_responsable" value="{{ old('nombre_responsable') }}">
                            @error('nombre_responsable')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipo_bien" class="form-label">Tipo Bien</label>
                            <input type="text" class="form-control @error('tipo_bien') is-invalid @enderror" 
                                   id="tipo_bien" name="tipo_bien" value="{{ old('tipo_bien') }}">
                            @error('tipo_bien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <input type="text" class="form-control @error('proveedor') is-invalid @enderror" 
                                   id="proveedor" name="proveedor" value="{{ old('proveedor') }}">
                            @error('proveedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea class="form-control @error('observacion') is-invalid @enderror" 
                                      id="observacion" name="observacion" rows="3">{{ old('observacion') }}</textarea>
                            @error('observacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('activos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-save"></i> Guardar Activo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Validación del formulario
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endpush
