@extends('layouts.app')

@section('title', 'Ayuda - Importaciones')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-upload"></i> Ayuda - Importaciones
                </h2>
                <a href="{{ route('ayuda.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Centro de Ayuda
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>¿Qué son las importaciones?</h4>
                        <p class="text-muted">
                            Las importaciones te permiten cargar múltiples activos al sistema desde archivos Excel, 
                            facilitando la migración de datos y la creación masiva de registros.
                        </p>

                        <h5 class="mt-4">Funcionalidades disponibles</h5>
                        
                        <div class="accordion" id="accordionImportaciones">
                            <!-- Ver importaciones -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingVerImportaciones">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVerImportaciones">
                                        <i class="bi bi-list-ul me-2"></i> Ver historial de importaciones
                                    </button>
                                </h2>
                                <div id="collapseVerImportaciones" class="accordion-collapse collapse show" data-bs-parent="#accordionImportaciones">
                                    <div class="accordion-body">
                                        <p>Para ver el historial de importaciones:</p>
                                        <ol>
                                            <li>Haz clic en <strong>"Importaciones"</strong> en el menú principal</li>
                                            <li>Verás una tabla con todas las importaciones realizadas</li>
                                            <li>Cada registro muestra: fecha, archivo, estado y resultados</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Crear nueva importación -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingNuevaImportacion">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNuevaImportacion">
                                        <i class="bi bi-plus-circle me-2"></i> Crear nueva importación
                                    </button>
                                </h2>
                                <div id="collapseNuevaImportacion" class="accordion-collapse collapse" data-bs-parent="#accordionImportaciones">
                                    <div class="accordion-body">
                                        <p>Para crear una nueva importación:</p>
                                        <ol>
                                            <li>En la lista de importaciones, haz clic en <strong>"Nueva Importación"</strong></li>
                                            <li>Selecciona el archivo Excel con los datos de los activos</li>
                                            <li>Haz clic en <strong>"Subir Archivo"</strong></li>
                                            <li>El sistema procesará el archivo y mostrará los resultados</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Descargar plantilla -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPlantilla">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePlantilla">
                                        <i class="bi bi-download me-2"></i> Descargar plantilla
                                    </button>
                                </h2>
                                <div id="collapsePlantilla" class="accordion-collapse collapse" data-bs-parent="#accordionImportaciones">
                                    <div class="accordion-body">
                                        <p>Para descargar la plantilla de Excel:</p>
                                        <ol>
                                            <li>Haz clic en <strong>"Descargar Plantilla"</strong></li>
                                            <li>Se descargará un archivo Excel con el formato correcto</li>
                                            <li>La plantilla incluye:</li>
                                            <ul>
                                                <li>Encabezados de columnas requeridas</li>
                                                <li>Ejemplos de datos</li>
                                                <li>Instrucciones de formato</li>
                                            </ul>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Formato del archivo -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFormato">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFormato">
                                        <i class="bi bi-file-earmark-excel me-2"></i> Formato del archivo Excel
                                    </button>
                                </h2>
                                <div id="collapseFormato" class="accordion-collapse collapse" data-bs-parent="#accordionImportaciones">
                                    <div class="accordion-body">
                                        <p>El archivo Excel debe tener las siguientes columnas:</p>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Columna</th>
                                                        <th>Descripción</th>
                                                        <th>Requerido</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><code>codigo</code></td>
                                                        <td>Código único del activo</td>
                                                        <td><span class="badge bg-danger">Sí</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><code>nombre</code></td>
                                                        <td>Nombre del activo</td>
                                                        <td><span class="badge bg-danger">Sí</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><code>valor_compra</code></td>
                                                        <td>Valor de compra</td>
                                                        <td><span class="badge bg-danger">Sí</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><code>fecha_compra</code></td>
                                                        <td>Fecha de compra (YYYY-MM-DD)</td>
                                                        <td><span class="badge bg-warning">Opcional</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><code>estado</code></td>
                                                        <td>Estado del activo</td>
                                                        <td><span class="badge bg-warning">Opcional</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><code>ubicacion</code></td>
                                                        <td>Ubicación del activo</td>
                                                        <td><span class="badge bg-warning">Opcional</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><code>codigo_responsable</code></td>
                                                        <td>Código del responsable</td>
                                                        <td><span class="badge bg-warning">Opcional</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ver detalles de importación -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingDetallesImportacion">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetallesImportacion">
                                        <i class="bi bi-info-circle me-2"></i> Ver detalles de importación
                                    </button>
                                </h2>
                                <div id="collapseDetallesImportacion" class="accordion-collapse collapse" data-bs-parent="#accordionImportaciones">
                                    <div class="accordion-body">
                                        <p>Para ver los detalles de una importación:</p>
                                        <ol>
                                            <li>En la lista de importaciones, haz clic en el botón <i class="bi bi-eye text-primary"></i> (Ver)</li>
                                            <li>Se mostrará información detallada incluyendo:</li>
                                            <ul>
                                                <li>Archivo subido</li>
                                                <li>Fecha y hora de importación</li>
                                                <li>Estado del proceso</li>
                                                <li>Número de registros procesados</li>
                                                <li>Errores encontrados (si los hay)</li>
                                                <li>Activos creados exitosamente</li>
                                            </ul>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Estados de importación -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEstadosImportacion">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEstadosImportacion">
                                        <i class="bi bi-tags me-2"></i> Estados de importación
                                    </button>
                                </h2>
                                <div id="collapseEstadosImportacion" class="accordion-collapse collapse" data-bs-parent="#accordionImportaciones">
                                    <div class="accordion-body">
                                        <p>Las importaciones pueden tener los siguientes estados:</p>
                                        <ul>
                                            <li><span class="badge bg-primary">Pendiente</span> - Esperando procesamiento</li>
                                            <li><span class="badge bg-warning">Procesando</span> - En proceso de importación</li>
                                            <li><span class="badge bg-success">Completada</span> - Importación exitosa</li>
                                            <li><span class="badge bg-danger">Error</span> - Error en el procesamiento</li>
                                            <li><span class="badge bg-info">Parcial</span> - Algunos registros procesados con errores</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Consejos útiles</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Usa la plantilla para evitar errores de formato</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Verifica que los códigos sean únicos</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Revisa los errores antes de corregir el archivo</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Importa en lotes pequeños para mejor rendimiento</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Errores comunes</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-x-circle text-danger"></i>
                                        <small>Códigos duplicados</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-x-circle text-danger"></i>
                                        <small>Formato de fecha incorrecto</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-x-circle text-danger"></i>
                                        <small>Valores numéricos con formato incorrecto</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-x-circle text-danger"></i>
                                        <small>Archivo corrupto o en formato incorrecto</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Límites</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-1">
                                        <strong>Tamaño máximo:</strong> 10 MB
                                    </li>
                                    <li class="mb-1">
                                        <strong>Registros máximos:</strong> 1000 por archivo
                                    </li>
                                    <li class="mb-1">
                                        <strong>Formatos soportados:</strong> .xlsx, .xls
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
