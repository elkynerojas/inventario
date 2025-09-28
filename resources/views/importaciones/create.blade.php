@extends('layouts.app')

@section('title', 'Nueva Importación de Activos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-upload me-2"></i>Nueva Importación de Activos
                </h1>
                <a href="{{ route('importaciones.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Formulario de subida -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-cloud-upload me-2"></i>Subir Archivo Excel
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('importaciones.store') }}" method="POST" enctype="multipart/form-data" id="formImportacion">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="archivo" class="form-label">Seleccionar Archivo</label>
                                    <input type="file" class="form-control @error('archivo') is-invalid @enderror" 
                                           id="archivo" name="archivo" accept=".xlsx,.xls,.csv" required>
                                    @error('archivo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Formatos permitidos: Excel (.xlsx, .xls) o CSV. Tamaño máximo: 10MB
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('importaciones.index') }}" class="btn btn-secondary me-md-2">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="btnSubir">
                                        <i class="bi bi-upload me-1"></i>Subir y Procesar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Información y plantilla -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>Información
                            </h5>
                        </div>
                        <div class="card-body">
                            <h6 class="text-primary">Campos Requeridos:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success me-2"></i><strong>codigo</strong> - Código único del activo</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i><strong>nombre</strong> - Nombre del activo</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i><strong>estado</strong> - Estado del activo (bueno, regular, malo, dado de baja)</li>
                            </ul>

                            <h6 class="text-primary mt-3">Campos Opcionales:</h6>
                            <ul class="list-unstyled small">
                                <li>• valor_compra</li>
                                <li>• fecha_compra</li>
                                <li>• marca, modelo, serial</li>
                                <li>• ubicacion</li>
                                <li>• nombre_responsable</li>
                                <li>• <strong>codigo_responsable</strong> - Número de documento del usuario (crea asignación automática)</li>
                                <li>• tipo_bien</li>
                                <li>• observacion, descripcion</li>
                                <li>• Y muchos más...</li>
                            </ul>

                            <div class="mt-4">
                                <a href="{{ route('importaciones.plantilla') }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-download me-1"></i>Descargar Plantilla
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightbulb me-2"></i>Consejos
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Antes de subir:</h6>
                                <ul class="mb-0 small">
                                    <li>Usa el formato de fecha YYYY-MM-DD</li>
                                    <li>Los valores numéricos deben usar punto decimal</li>
                                    <li>El campo <strong>codigo_responsable</strong> debe coincidir con el número de documento de un usuario existente</li>
                                    <li>Revisa la plantilla de ejemplo</li>
                                </ul>
                            </div>

                            <div class="alert alert-warning">
                                <h6 class="alert-heading">Procesamiento en Tiempo Real:</h6>
                                <p class="mb-0 small">
                                    El archivo se procesará inmediatamente después de subirlo. 
                                    Por favor espera hasta que se complete la importación.
                                </p>
                            </div>

                            <div class="alert alert-warning">
                                <h6 class="alert-heading">Actualización de Activos:</h6>
                                <p class="mb-0 small">
                                    Si un activo con el mismo <strong>código</strong> ya existe, se actualizará con los nuevos datos. 
                                    Los campos vacíos en el Excel mantendrán los valores actuales del activo.
                                </p>
                            </div>

                            <div class="alert alert-success">
                                <h6 class="alert-heading">Asignación Automática:</h6>
                                <p class="mb-0 small">
                                    Si incluyes el campo <strong>codigo_responsable</strong> con el número de documento de un usuario, 
                                    se creará automáticamente una asignación del activo a ese usuario. Si ya existe una asignación a otro usuario, 
                                    se marcará como "devuelta" y se creará la nueva asignación.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de carga -->
<div class="modal fade" id="modalCarga" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <h5>Procesando archivo...</h5>
                <p class="text-muted">Por favor espera mientras se procesa tu archivo Excel.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('formImportacion').addEventListener('submit', function(e) {
    const archivo = document.getElementById('archivo').files[0];
    
    if (!archivo) {
        e.preventDefault();
        alert('Por favor selecciona un archivo');
        return;
    }

    // Mostrar modal de carga
    const modal = new bootstrap.Modal(document.getElementById('modalCarga'));
    modal.show();
    
    // Deshabilitar botón
    document.getElementById('btnSubir').disabled = true;
});

// Validación de archivo en tiempo real
document.getElementById('archivo').addEventListener('change', function(e) {
    const archivo = e.target.files[0];
    const btnSubir = document.getElementById('btnSubir');
    
    if (archivo) {
        // Validar tamaño (10MB)
        if (archivo.size > 10 * 1024 * 1024) {
            alert('El archivo es demasiado grande. El tamaño máximo es 10MB.');
            e.target.value = '';
            btnSubir.disabled = true;
            return;
        }
        
        // Validar extensión
        const extensionesPermitidas = ['.xlsx', '.xls', '.csv'];
        const extension = archivo.name.toLowerCase().substring(archivo.name.lastIndexOf('.'));
        
        if (!extensionesPermitidas.includes(extension)) {
            alert('Formato de archivo no válido. Solo se permiten archivos Excel (.xlsx, .xls) o CSV.');
            e.target.value = '';
            btnSubir.disabled = true;
            return;
        }
        
        btnSubir.disabled = false;
    } else {
        btnSubir.disabled = true;
    }
});
</script>
@endsection
