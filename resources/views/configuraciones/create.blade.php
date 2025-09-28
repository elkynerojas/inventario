@extends('layouts.app')

@section('title', 'Nueva Configuración')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header de la página -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-plus-lg"></i> Nueva Configuración</h2>
                    <a href="{{ route('configuraciones.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de la Configuración</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('configuraciones.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Clave -->
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label">Clave <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('clave') is-invalid @enderror" 
                                   id="clave" name="clave" value="{{ old('clave') }}" required>
                            @error('clave')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Identificador único para la configuración (ej: nombre_colegio)</div>
                        </div>

                        <!-- Categoría -->
                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                            <select class="form-select @error('categoria') is-invalid @enderror" 
                                    id="categoria" name="categoria" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $key => $value)
                                    <option value="{{ $key }}" {{ old('categoria') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nombre descriptivo de la configuración</div>
                        </div>

                        <!-- Tipo -->
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipo') is-invalid @enderror" 
                                    id="tipo" name="tipo" required onchange="toggleValueInput()">
                                <option value="">Seleccionar tipo</option>
                                @foreach($tipos as $key => $value)
                                    <option value="{{ $key }}" {{ old('tipo') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Descripción detallada de qué hace esta configuración</div>
                    </div>

                    <!-- Valor -->
                    <div class="mb-3" id="valor-container">
                        <label for="valor" class="form-label">Valor</label>
                        <div id="valor-input">
                            <input type="text" class="form-control @error('valor') is-invalid @enderror" 
                                   id="valor" name="valor" value="{{ old('valor') }}">
                        </div>
                        @error('valor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Archivo (para tipo file) -->
                    <div class="mb-3" id="archivo-container" style="display: none;">
                        <label for="archivo" class="form-label">Archivo</label>
                        <input type="file" class="form-control @error('archivo') is-invalid @enderror" 
                               id="archivo" name="archivo">
                        @error('archivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Opciones (para selects) -->
                    <div class="mb-3" id="opciones-container" style="display: none;">
                        <label for="opciones" class="form-label">Opciones</label>
                        <textarea class="form-control @error('opciones') is-invalid @enderror" 
                                  id="opciones" name="opciones" rows="3" 
                                  placeholder='{"opcion1": "Valor 1", "opcion2": "Valor 2"}'></textarea>
                        @error('opciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Formato JSON para opciones de select</div>
                    </div>

                    <div class="row">
                        <!-- Orden -->
                        <div class="col-md-4 mb-3">
                            <label for="orden" class="form-label">Orden</label>
                            <input type="number" class="form-control @error('orden') is-invalid @enderror" 
                                   id="orden" name="orden" value="{{ old('orden', 0) }}" min="0">
                            @error('orden')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Requerido -->
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="requerido" name="requerido" 
                                       value="1" {{ old('requerido') ? 'checked' : '' }}>
                                <label class="form-check-label" for="requerido">
                                    Configuración requerida
                                </label>
                            </div>
                        </div>

                        <!-- Activo -->
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                       value="1" {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    Configuración activa
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('configuraciones.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Crear Configuración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleValueInput() {
    const tipo = document.getElementById('tipo').value;
    const valorContainer = document.getElementById('valor-container');
    const archivoContainer = document.getElementById('archivo-container');
    const opcionesContainer = document.getElementById('opciones-container');
    const valorInput = document.getElementById('valor-input');

    // Ocultar todos los contenedores
    archivoContainer.style.display = 'none';
    opcionesContainer.style.display = 'none';

    if (tipo === 'file') {
        valorContainer.style.display = 'none';
        archivoContainer.style.display = 'block';
    } else if (tipo === 'boolean') {
        valorContainer.style.display = 'block';
        valorInput.innerHTML = `
            <select class="form-control @error('valor') is-invalid @enderror" id="valor" name="valor">
                <option value="false">No</option>
                <option value="true">Sí</option>
            </select>
        `;
    } else if (tipo === 'json') {
        valorContainer.style.display = 'block';
        valorInput.innerHTML = `
            <textarea class="form-control @error('valor') is-invalid @enderror" 
                      id="valor" name="valor" rows="5" 
                      placeholder='{"clave": "valor"}'></textarea>
        `;
    } else {
        valorContainer.style.display = 'block';
        valorInput.innerHTML = `
            <input type="${tipo === 'number' || tipo === 'integer' || tipo === 'float' ? 'number' : 'text'}" 
                   class="form-control @error('valor') is-invalid @enderror" 
                   id="valor" name="valor" value="{{ old('valor') }}">
        `;
    }
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    toggleValueInput();
});
</script>
@endsection
