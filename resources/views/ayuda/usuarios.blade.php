@extends('layouts.app')

@section('title', 'Ayuda - Gestión de Usuarios')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-people"></i> Ayuda - Gestión de Usuarios
                </h2>
                <a href="{{ route('ayuda.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Centro de Ayuda
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>¿Qué es la gestión de usuarios?</h4>
                        <p class="text-muted">
                            La gestión de usuarios te permite administrar las cuentas de acceso al sistema, 
                            asignar roles y permisos, y controlar quién puede acceder a cada funcionalidad.
                        </p>

                        <h5 class="mt-4">Funcionalidades disponibles</h5>
                        
                        <div class="accordion" id="accordionUsuarios">
                            <!-- Ver usuarios -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingVerUsuarios">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVerUsuarios">
                                        <i class="bi bi-list-ul me-2"></i> Ver lista de usuarios
                                    </button>
                                </h2>
                                <div id="collapseVerUsuarios" class="accordion-collapse collapse show" data-bs-parent="#accordionUsuarios">
                                    <div class="accordion-body">
                                        <p>Para ver todos los usuarios:</p>
                                        <ol>
                                            <li>Haz clic en <strong>"Usuarios"</strong> en el menú principal</li>
                                            <li>Selecciona <strong>"Gestionar Usuarios"</strong></li>
                                            <li>Verás una tabla con todos los usuarios del sistema</li>
                                            <li>Puedes usar los filtros para buscar usuarios específicos</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Crear usuario -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCrearUsuario">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCrearUsuario">
                                        <i class="bi bi-person-plus me-2"></i> Crear nuevo usuario
                                    </button>
                                </h2>
                                <div id="collapseCrearUsuario" class="accordion-collapse collapse" data-bs-parent="#accordionUsuarios">
                                    <div class="accordion-body">
                                        <p>Para crear un nuevo usuario:</p>
                                        <ol>
                                            <li>En la lista de usuarios, haz clic en <strong>"Nuevo Usuario"</strong></li>
                                            <li>Completa el formulario con:</li>
                                            <ul>
                                                <li><strong>Nombre:</strong> Nombre completo del usuario</li>
                                                <li><strong>Email:</strong> Correo electrónico (debe ser único)</li>
                                                <li><strong>Documento:</strong> Número de identificación</li>
                                                <li><strong>Rol:</strong> Tipo de usuario (admin, estudiante, profesor, etc.)</li>
                                                <li><strong>Contraseña:</strong> Contraseña temporal</li>
                                            </ul>
                                            <li>Haz clic en <strong>"Crear Usuario"</strong></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Editar usuario -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEditarUsuario">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEditarUsuario">
                                        <i class="bi bi-pencil me-2"></i> Editar usuario existente
                                    </button>
                                </h2>
                                <div id="collapseEditarUsuario" class="accordion-collapse collapse" data-bs-parent="#accordionUsuarios">
                                    <div class="accordion-body">
                                        <p>Para editar un usuario:</p>
                                        <ol>
                                            <li>En la lista de usuarios, haz clic en el botón <i class="bi bi-pencil text-warning"></i> (Editar)</li>
                                            <li>Modifica los campos que necesites</li>
                                            <li>Haz clic en <strong>"Actualizar"</strong></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Restablecer contraseña -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingResetPassword">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResetPassword">
                                        <i class="bi bi-key me-2"></i> Restablecer contraseña
                                    </button>
                                </h2>
                                <div id="collapseResetPassword" class="accordion-collapse collapse" data-bs-parent="#accordionUsuarios">
                                    <div class="accordion-body">
                                        <p>Para restablecer la contraseña de un usuario:</p>
                                        <ol>
                                            <li>En la lista de usuarios, haz clic en <strong>"Restablecer Contraseña"</strong></li>
                                            <li>Ingresa la nueva contraseña</li>
                                            <li>Confirma la nueva contraseña</li>
                                            <li>Haz clic en <strong>"Restablecer"</strong></li>
                                        </ol>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> 
                                            <strong>Nota:</strong> El usuario deberá cambiar su contraseña en el próximo inicio de sesión.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Eliminar usuario -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEliminarUsuario">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEliminarUsuario">
                                        <i class="bi bi-trash me-2"></i> Eliminar usuario
                                    </button>
                                </h2>
                                <div id="collapseEliminarUsuario" class="accordion-collapse collapse" data-bs-parent="#accordionUsuarios">
                                    <div class="accordion-body">
                                        <p>Para eliminar un usuario:</p>
                                        <ol>
                                            <li>En la lista de usuarios, haz clic en el botón <i class="bi bi-trash text-danger"></i> (Eliminar)</li>
                                            <li>Confirma la eliminación</li>
                                        </ol>
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle"></i> 
                                            <strong>Restricciones:</strong>
                                            <ul class="mb-0">
                                                <li>No se puede eliminar un usuario con activos asignados</li>
                                                <li>No se puede eliminar el último administrador del sistema</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gestión de roles -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingRoles">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRoles">
                                        <i class="bi bi-person-badge me-2"></i> Gestionar roles
                                    </button>
                                </h2>
                                <div id="collapseRoles" class="accordion-collapse collapse" data-bs-parent="#accordionUsuarios">
                                    <div class="accordion-body">
                                        <p>Para gestionar roles:</p>
                                        <ol>
                                            <li>Haz clic en <strong>"Gestionar Roles"</strong> en el menú de usuarios</li>
                                            <li>Puedes crear, editar y eliminar roles</li>
                                            <li>Los roles disponibles incluyen:</li>
                                            <ul>
                                                <li><strong>admin:</strong> Acceso completo al sistema</li>
                                                <li><strong>estudiante:</strong> Solo puede ver sus asignaciones</li>
                                                <li><strong>profesor:</strong> Solo puede ver sus asignaciones</li>
                                                <li><strong>administrativo:</strong> Acceso limitado</li>
                                            </ul>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Importar usuarios -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingImportar">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseImportar">
                                        <i class="bi bi-upload me-2"></i> Importar usuarios masivamente
                                    </button>
                                </h2>
                                <div id="collapseImportar" class="accordion-collapse collapse" data-bs-parent="#accordionUsuarios">
                                    <div class="accordion-body">
                                        <p>Para importar usuarios desde Excel:</p>
                                        <ol>
                                            <li>Haz clic en <strong>"Importar Usuarios"</strong></li>
                                            <li>Descarga la plantilla de Excel</li>
                                            <li>Completa la plantilla con los datos de los usuarios</li>
                                            <li>Sube el archivo completado</li>
                                            <li>Revisa los resultados de la importación</li>
                                        </ol>
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle"></i> 
                                            <strong>Formato requerido:</strong> El archivo debe incluir columnas para nombre, email, documento, rol y contraseña.
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
                                        <small>Asigna roles apropiados a cada usuario</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Usa emails únicos para cada usuario</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Mantén al menos un administrador activo</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <small>Usa la importación para crear múltiples usuarios</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-shield-check"></i> Permisos por rol</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <h6 class="text-danger">Administrador</h6>
                                    <small class="text-muted">Acceso completo a todas las funcionalidades</small>
                                </div>
                                <div class="mb-2">
                                    <h6 class="text-primary">Estudiante/Profesor</h6>
                                    <small class="text-muted">Solo puede ver sus asignaciones y cambiar contraseña</small>
                                </div>
                                <div>
                                    <h6 class="text-secondary">Otros roles</h6>
                                    <small class="text-muted">Permisos limitados según configuración</small>
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
