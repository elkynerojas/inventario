@extends('layouts.app')

@section('title', 'Ayuda - Asignaciones')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-person-check"></i> Ayuda - Asignaciones
                </h2>
                <a href="{{ route('ayuda.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Centro de Ayuda
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>¿Qué son las asignaciones?</h4>
                        <p class="text-muted">
                            Las asignaciones son el registro de cuando un activo es entregado a un usuario específico. 
                            Cada asignación incluye información sobre el activo, el usuario asignado, fechas y estado.
                        </p>

                        <h5 class="mt-4">Funcionalidades disponibles</h5>
                        
                        <div class="accordion" id="accordionAsignaciones">
                            <!-- Ver asignaciones -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingVer">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVer">
                                        <i class="bi bi-eye me-2"></i> Ver mis asignaciones
                                    </button>
                                </h2>
                                <div id="collapseVer" class="accordion-collapse collapse show" data-bs-parent="#accordionAsignaciones">
                                    <div class="accordion-body">
                                        <p>Para ver tus asignaciones:</p>
                                        <ol>
                                            <li>Haz clic en <strong>"Asignaciones"</strong> en el menú principal</li>
                                            <li>Verás una tabla con todas tus asignaciones</li>
                                            <li>La tabla muestra: ID, Activo, Fecha de asignación, Estado y Asignado por</li>
                                        </ol>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> 
                                            <strong>Nota:</strong> Solo puedes ver las asignaciones que te han sido asignadas.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ver detalles -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingDetalles">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetalles">
                                        <i class="bi bi-info-circle me-2"></i> Ver detalles de una asignación
                                    </button>
                                </h2>
                                <div id="collapseDetalles" class="accordion-collapse collapse" data-bs-parent="#accordionAsignaciones">
                                    <div class="accordion-body">
                                        <p>Para ver los detalles completos de una asignación:</p>
                                        <ol>
                                            <li>En la lista de asignaciones, haz clic en el botón <i class="bi bi-eye text-primary"></i> (Ver)</li>
                                            <li>Se abrirá una página con información detallada que incluye:</li>
                                            <ul>
                                                <li>Información del activo (código, nombre, marca, modelo, serial, estado)</li>
                                                <li>Información del usuario asignado</li>
                                                <li>Fechas de asignación y devolución</li>
                                                <li>Observaciones</li>
                                                <li>Estadísticas del activo</li>
                                            </ul>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Estados de asignación -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEstados">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEstados">
                                        <i class="bi bi-tags me-2"></i> Estados de asignación
                                    </button>
                                </h2>
                                <div id="collapseEstados" class="accordion-collapse collapse" data-bs-parent="#accordionAsignaciones">
                                    <div class="accordion-body">
                                        <p>Las asignaciones pueden tener los siguientes estados:</p>
                                        <ul>
                                            <li><span class="badge bg-success">Activa</span> - El activo está actualmente asignado al usuario</li>
                                            <li><span class="badge bg-info">Devuelta</span> - El activo fue devuelto y ya no está asignado</li>
                                            <li><span class="badge bg-warning">Perdida</span> - El activo fue reportado como perdido</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Cambiar contraseña -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPassword">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePassword">
                                        <i class="bi bi-key me-2"></i> Cambiar mi contraseña
                                    </button>
                                </h2>
                                <div id="collapsePassword" class="accordion-collapse collapse" data-bs-parent="#accordionAsignaciones">
                                    <div class="accordion-body">
                                        <p>Para cambiar tu contraseña:</p>
                                        <ol>
                                            <li>Haz clic en tu nombre en la esquina superior derecha</li>
                                            <li>Selecciona <strong>"Perfil"</strong></li>
                                            <li>En la sección "Cambiar Contraseña":</li>
                                            <ul>
                                                <li>Ingresa tu contraseña actual</li>
                                                <li>Ingresa tu nueva contraseña</li>
                                                <li>Confirma la nueva contraseña</li>
                                            </ul>
                                            <li>Haz clic en <strong>"Guardar"</strong></li>
                                        </ol>
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle"></i> 
                                            <strong>Importante:</strong> La nueva contraseña debe tener al menos 8 caracteres.
                                        </div>
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
                                        <small>Revisa regularmente tus asignaciones activas</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Mantén tu contraseña segura y cámbiala periódicamente</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Contacta al administrador si encuentras algún problema</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-question-circle"></i> ¿Necesitas ayuda?</h6>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted">
                                    Si tienes dudas adicionales o encuentras algún problema, 
                                    contacta al administrador del sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
