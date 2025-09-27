@extends('layouts.app')

@section('title', 'Crear Rol - Sistema de Inventario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-person-plus"></i> Crear Nuevo Rol</h2>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <!-- Nombre del rol -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            Nombre del Rol <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre') }}"
                               class="form-control @error('nombre') is-invalid @enderror"
                               placeholder="Ej: profesor, estudiante, coordinador"
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">El nombre será convertido automáticamente a minúsculas.</div>
                    </div>

                    <!-- Información sobre roles -->
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill me-3"></i>
                            <div>
                                <h6 class="alert-heading">Información sobre Roles</h6>
                                <ul class="mb-0">
                                    <li>Los roles definen los permisos de los usuarios en el sistema</li>
                                    <li>El rol "admin" tiene acceso completo al sistema</li>
                                    <li>Los roles "profesor" y "estudiante" tienen acceso limitado</li>
                                    <li>Puede crear roles personalizados según sus necesidades</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Crear Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection