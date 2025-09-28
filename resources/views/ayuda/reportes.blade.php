@extends('layouts.app')

@section('title', 'Ayuda - Reportes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-graph-up"></i> Ayuda - Reportes
                </h2>
                <a href="{{ route('ayuda.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Centro de Ayuda
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>¿Qué son los reportes?</h4>
                        <p class="text-muted">
                            Los reportes te permiten generar documentos en Excel y PDF con información detallada 
                            de los activos, incluyendo filtros personalizados y estadísticas.
                        </p>

                        <h5 class="mt-4">Funcionalidades disponibles</h5>
                        
                        <div class="accordion" id="accordionReportes">
                            <!-- Ver reportes -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingVerReportes">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVerReportes">
                                        <i class="bi bi-list-ul me-2"></i> Ver reportes de activos
                                    </button>
                                </h2>
                                <div id="collapseVerReportes" class="accordion-collapse collapse show" data-bs-parent="#accordionReportes">
                                    <div class="accordion-body">
                                        <p>Para ver los reportes de activos:</p>
                                        <ol>
                                            <li>Haz clic en <strong>"Reportes"</strong> en el menú principal</li>
                                            <li>Verás una tabla con todos los activos y sus filtros</li>
                                            <li>Puedes aplicar filtros para personalizar la vista</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Exportar a Excel -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingExcel">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExcel">
                                        <i class="bi bi-file-earmark-excel me-2"></i> Exportar a Excel
                                    </button>
                                </h2>
                                <div id="collapseExcel" class="accordion-collapse collapse" data-bs-parent="#accordionReportes">
                                    <div class="accordion-body">
                                        <p>Para exportar un reporte a Excel:</p>
                                        <ol>
                                            <li>En la página de reportes, aplica los filtros que necesites</li>
                                            <li>Haz clic en <strong>"Exportar a Excel"</strong></li>
                                            <li>El archivo se descargará automáticamente</li>
                                            <li>El archivo incluye todos los campos de los activos</li>
                                        </ol>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> 
                                            <strong>Nota:</strong> El archivo Excel incluye información completa de todos los activos.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Exportar a PDF -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPDF">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePDF">
                                        <i class="bi bi-file-earmark-pdf me-2"></i> Exportar a PDF
                                    </button>
                                </h2>
                                <div id="collapsePDF" class="accordion-collapse collapse" data-bs-parent="#accordionReportes">
                                    <div class="accordion-body">
                                        <p>Para exportar un reporte a PDF:</p>
                                        <ol>
                                            <li>En la página de reportes, aplica los filtros que necesites</li>
                                            <li>Haz clic en <strong>"Exportar a PDF"</strong></li>
                                            <li>El archivo se descargará automáticamente</li>
                                            <li>El PDF incluye un resumen con estadísticas</li>
                                        </ol>
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle"></i> 
                                            <strong>Límite:</strong> El PDF se limita a 500 registros para optimizar el rendimiento.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtros de reportes -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFiltrosReportes">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltrosReportes">
                                        <i class="bi bi-funnel me-2"></i> Usar filtros en reportes
                                    </button>
                                </h2>
                                <div id="collapseFiltrosReportes" class="accordion-collapse collapse" data-bs-parent="#accordionReportes">
                                    <div class="accordion-body">
                                        <p>Para filtrar los reportes:</p>
                                        <ol>
                                            <li>Utiliza el campo de búsqueda general</li>
                                            <li>Aplica filtros por:</li>
                                            <ul>
                                                <li><strong>Estado:</strong> Bueno, Regular, Malo, Dado de baja</li>
                                                <li><strong>Ubicación:</strong> Dónde se encuentra el activo</li>
                                                <li><strong>Responsable:</strong> Usuario asignado</li>
                                                <li><strong>Rango de valor:</strong> Valor mínimo y máximo</li>
                                                <li><strong>Rango de fechas:</strong> Fecha de compra</li>
                                            </ul>
                                            <li>Haz clic en <strong>"Filtrar"</strong></li>
                                            <li>Los filtros se aplicarán tanto a la vista como a las exportaciones</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEstadisticas">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEstadisticas">
                                        <i class="bi bi-bar-chart me-2"></i> Ver estadísticas
                                    </button>
                                </h2>
                                <div id="collapseEstadisticas" class="accordion-collapse collapse" data-bs-parent="#accordionReportes">
                                    <div class="accordion-body">
                                        <p>Para ver estadísticas de los activos:</p>
                                        <ol>
                                            <li>En la página de reportes, verás tarjetas con estadísticas</li>
                                            <li>Las estadísticas incluyen:</li>
                                            <ul>
                                                <li><strong>Total de activos</strong></li>
                                                <li><strong>Activos en buen estado</strong></li>
                                                <li><strong>Activos en estado regular</strong></li>
                                                <li><strong>Activos en mal estado</strong></li>
                                                <li><strong>Activos dados de baja</strong></li>
                                            </ul>
                                            <li>Las estadísticas se actualizan según los filtros aplicados</li>
                                        </ol>
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
                                        <small>Usa filtros para generar reportes específicos</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>El Excel es ideal para análisis detallado</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>El PDF es perfecto para presentaciones</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Revisa las estadísticas para tomar decisiones</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Tipos de exportación</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-success">Excel (.xlsx)</h6>
                                    <small class="text-muted">
                                        Incluye todos los campos de los activos. Ideal para análisis y procesamiento de datos.
                                    </small>
                                </div>
                                <div>
                                    <h6 class="text-danger">PDF (.pdf)</h6>
                                    <small class="text-muted">
                                        Formato de presentación con estadísticas. Limitado a 500 registros.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
