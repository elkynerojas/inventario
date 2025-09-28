@extends('layouts.app')

@section('title', 'Centro de Ayuda - Sistema de Inventario')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <i class="bi bi-question-circle"></i> Centro de Ayuda
                </h2>
                <p class="text-muted mb-0">Encuentra instrucciones detalladas para usar todas las funcionalidades del sistema</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Asignaciones - Disponible para todos -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi bi-person-check display-4 text-primary"></i>
                                </div>
                                <h5 class="card-title">Asignaciones</h5>
                                <p class="card-text">Aprende a consultar y gestionar las asignaciones de activos</p>
                                <a href="{{ route('ayuda.asignaciones') }}" class="btn btn-primary">
                                    <i class="bi bi-book"></i> Ver Instrucciones
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->esAdmin())
                    <!-- Activos - Solo administradores -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi bi-box display-4 text-success"></i>
                                </div>
                                <h5 class="card-title">Gestión de Activos</h5>
                                <p class="card-text">Instrucciones para crear, editar y administrar activos</p>
                                <a href="{{ route('ayuda.activos') }}" class="btn btn-success">
                                    <i class="bi bi-book"></i> Ver Instrucciones
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reportes - Solo administradores -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi bi-graph-up display-4 text-info"></i>
                                </div>
                                <h5 class="card-title">Reportes</h5>
                                <p class="card-text">Genera reportes en Excel y PDF de los activos</p>
                                <a href="{{ route('ayuda.reportes') }}" class="btn btn-info">
                                    <i class="bi bi-book"></i> Ver Instrucciones
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Usuarios - Solo administradores -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-warning">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi bi-people display-4 text-warning"></i>
                                </div>
                                <h5 class="card-title">Gestión de Usuarios</h5>
                                <p class="card-text">Administra usuarios, roles y permisos del sistema</p>
                                <a href="{{ route('ayuda.usuarios') }}" class="btn btn-warning">
                                    <i class="bi bi-book"></i> Ver Instrucciones
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Importaciones - Solo administradores -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-secondary">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi bi-upload display-4 text-secondary"></i>
                                </div>
                                <h5 class="card-title">Importaciones</h5>
                                <p class="card-text">Importa activos masivamente desde archivos Excel</p>
                                <a href="{{ route('ayuda.importaciones') }}" class="btn btn-secondary">
                                    <i class="bi bi-book"></i> Ver Instrucciones
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Información general -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5><i class="bi bi-info-circle"></i> Información General</h5>
                            <ul class="mb-0">
                                <li><strong>Navegación:</strong> Utiliza el menú superior para acceder a las diferentes secciones</li>
                                <li><strong>Perfil:</strong> Puedes cambiar tu contraseña desde el menú de perfil</li>
                                <li><strong>Soporte:</strong> Si tienes dudas adicionales, contacta al administrador del sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
