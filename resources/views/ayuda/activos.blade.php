@extends('layouts.app')

@section('title', 'Ayuda - Gestión de Activos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-box"></i> Ayuda - Gestión de Activos
                </h2>
                <a href="{{ route('ayuda.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Centro de Ayuda
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>¿Qué son los activos?</h4>
                        <p class="text-muted">
                            Los activos son todos los bienes muebles e inmuebles que posee la organización. 
                            Cada activo tiene información detallada como código, nombre, valor, ubicación, etc.
                        </p>

                        <h5 class="mt-4">Funcionalidades disponibles</h5>
                        
                        <div class="accordion" id="accordionActivos">
                            <!-- Ver activos -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingVerActivos">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVerActivos">
                                        <i class="bi bi-list-ul me-2"></i> Ver lista de activos
                                    </button>
                                </h2>
                                <div id="collapseVerActivos" class="accordion-collapse collapse show" data-bs-parent="#accordionActivos">
                                    <div class="accordion-body">
                                        <p>Para ver todos los activos:</p>
                                        <ol>
                                            <li>Haz clic en <strong>"Activos"</strong> en el menú principal</li>
                                            <li>Verás una tabla con todos los activos del sistema</li>
                                            <li>Puedes usar los filtros para buscar activos específicos</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Crear activo -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCrear">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCrear">
                                        <i class="bi bi-plus-circle me-2"></i> Crear nuevo activo
                                    </button>
                                </h2>
                                <div id="collapseCrear" class="accordion-collapse collapse" data-bs-parent="#accordionActivos">
                                    <div class="accordion-body">
                                        <p>Para crear un nuevo activo:</p>
                                        <ol>
                                            <li>En la lista de activos, haz clic en <strong>"Nuevo Activo"</strong></li>
                                            <li>Completa el formulario con la información requerida:</li>
                                            <ul>
                                                <li><strong>Código:</strong> Identificador único del activo</li>
                                                <li><strong>Nombre:</strong> Descripción del activo</li>
                                                <li><strong>Valor de compra:</strong> Costo del activo</li>
                                                <li><strong>Fecha de compra:</strong> Cuando se adquirió</li>
                                                <li><strong>Estado:</strong> Bueno, Regular, Malo, Dado de baja</li>
                                                <li><strong>Ubicación:</strong> Dónde se encuentra</li>
                                            </ul>
                                            <li>Haz clic en <strong>"Guardar"</strong></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Editar activo -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEditar">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEditar">
                                        <i class="bi bi-pencil me-2"></i> Editar activo existente
                                    </button>
                                </h2>
                                <div id="collapseEditar" class="accordion-collapse collapse" data-bs-parent="#accordionActivos">
                                    <div class="accordion-body">
                                        <p>Para editar un activo:</p>
                                        <ol>
                                            <li>En la lista de activos, haz clic en el botón <i class="bi bi-pencil text-warning"></i> (Editar)</li>
                                            <li>Modifica los campos que necesites</li>
                                            <li>Haz clic en <strong>"Actualizar"</strong></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Ver detalles -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingDetallesActivo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetallesActivo">
                                        <i class="bi bi-info-circle me-2"></i> Ver detalles del activo
                                    </button>
                                </h2>
                                <div id="collapseDetallesActivo" class="accordion-collapse collapse" data-bs-parent="#accordionActivos">
                                    <div class="accordion-body">
                                        <p>Para ver los detalles completos de un activo:</p>
                                        <ol>
                                            <li>En la lista de activos, haz clic en el botón <i class="bi bi-eye text-primary"></i> (Ver)</li>
                                            <li>Se mostrará información detallada incluyendo:</li>
                                            <ul>
                                                <li>Información básica del activo</li>
                                                <li>Historial de asignaciones</li>
                                                <li>Estadísticas de uso</li>
                                            </ul>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Dar de baja -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingBaja">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBaja">
                                        <i class="bi bi-trash me-2"></i> Dar de baja un activo
                                    </button>
                                </h2>
                                <div id="collapseBaja" class="accordion-collapse collapse" data-bs-parent="#accordionActivos">
                                    <div class="accordion-body">
                                        <p>Para dar de baja un activo:</p>
                                        <ol>
                                            <li>En la lista de activos, haz clic en <strong>"Dar de Baja"</strong></li>
                                            <li>Completa el formulario con:</li>
                                            <ul>
                                                <li><strong>Motivo de baja:</strong> Razón por la cual se da de baja</li>
                                                <li><strong>Fecha de baja:</strong> Cuándo se da de baja</li>
                                                <li><strong>Observaciones:</strong> Información adicional</li>
                                            </ul>
                                            <li>Haz clic en <strong>"Confirmar Baja"</strong></li>
                                        </ol>
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle"></i> 
                                            <strong>Importante:</strong> Esta acción no se puede deshacer.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtros -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFiltros">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros">
                                        <i class="bi bi-funnel me-2"></i> Usar filtros de búsqueda
                                    </button>
                                </h2>
                                <div id="collapseFiltros" class="accordion-collapse collapse" data-bs-parent="#accordionActivos">
                                    <div class="accordion-body">
                                        <p>Para buscar activos específicos:</p>
                                        <ol>
                                            <li>Utiliza el campo de búsqueda general</li>
                                            <li>Aplica filtros por:</li>
                                            <ul>
                                                <li><strong>Estado:</strong> Bueno, Regular, Malo, Dado de baja</li>
                                                <li><strong>Ubicación:</strong> Dónde se encuentra</li>
                                                <li><strong>Responsable:</strong> Usuario asignado</li>
                                                <li><strong>Rango de valor:</strong> Valor mínimo y máximo</li>
                                                <li><strong>Rango de fechas:</strong> Fecha de compra</li>
                                            </ul>
                                            <li>Haz clic en <strong>"Filtrar"</strong></li>
                                            <li>Para limpiar filtros, haz clic en <strong>"Limpiar"</strong></li>
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
                                        <small>Usa códigos únicos y descriptivos para los activos</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Mantén actualizada la información de ubicación</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Revisa regularmente el estado de los activos</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Usa los filtros para encontrar activos rápidamente</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Estados de activos</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-1"><span class="badge bg-success">Bueno</span> - En excelente estado</li>
                                    <li class="mb-1"><span class="badge bg-warning">Regular</span> - Funcional con desgaste</li>
                                    <li class="mb-1"><span class="badge bg-danger">Malo</span> - Requiere mantenimiento</li>
                                    <li class="mb-1"><span class="badge bg-secondary">Dado de baja</span> - No disponible</li>
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
