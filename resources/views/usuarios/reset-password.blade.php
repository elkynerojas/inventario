@extends('layouts.app')

@section('title', 'Restablecer Contraseña - Sistema de Inventario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-key"></i> Restablecer Contraseña - {{ $usuario->name }}</h2>
                    <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Información del usuario -->
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                            {{ strtoupper(substr($usuario->name, 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $usuario->name }}</h6>
                            <small class="text-muted">{{ $usuario->email }}</small><br>
                            <small class="text-muted">Documento: {{ $usuario->documento }}</small>
                        </div>
                    </div>
                </div>

                <!-- Advertencia -->
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <i class="bi bi-exclamation-triangle-fill me-3"></i>
                        <div>
                            <h6 class="alert-heading">Advertencia</h6>
                            <p class="mb-0">Esta acción cambiará la contraseña del usuario. El usuario deberá usar la nueva contraseña para iniciar sesión.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('usuarios.reset-password', $usuario) }}">
                    @csrf
                    @method('PATCH')

                    <!-- Nueva contraseña -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Nueva Contraseña <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mínimo 8 caracteres"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">La contraseña debe tener al menos 8 caracteres.</div>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            Confirmar Nueva Contraseña <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="form-control"
                               placeholder="Repita la nueva contraseña"
                               required>
                    </div>

                    <!-- Información adicional -->
                    <div class="alert alert-primary">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill me-3"></i>
                            <div>
                                <h6 class="alert-heading">Información Importante</h6>
                                <ul class="mb-0">
                                    <li>La contraseña se cambiará inmediatamente</li>
                                    <li>El usuario recibirá un email de notificación (si está configurado)</li>
                                    <li>Se recomienda informar al usuario sobre el cambio</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="btn btn-warning"
                                onclick="return confirm('¿Está seguro de cambiar la contraseña de este usuario?')">
                            <i class="bi bi-key"></i> Restablecer Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection