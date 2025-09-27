@extends('layouts.app')

@section('title', 'Crear Usuario - Sistema de Inventario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-person-plus"></i> Crear Nuevo Usuario</h2>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.store') }}">
                    @csrf

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Nombre Completo <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Ingrese el nombre completo"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Correo Electrónico <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="usuario@ejemplo.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Documento -->
                    <div class="mb-3">
                        <label for="documento" class="form-label">
                            Número de Documento <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="documento" 
                               name="documento" 
                               value="{{ old('documento') }}"
                               class="form-control @error('documento') is-invalid @enderror"
                               placeholder="12345678"
                               required>
                        @error('documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Rol -->
                    <div class="mb-3">
                        <label for="rol_id" class="form-label">
                            Rol <span class="text-danger">*</span>
                        </label>
                        <select id="rol_id" 
                                name="rol_id" 
                                class="form-select @error('rol_id') is-invalid @enderror"
                                required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                    {{ ucfirst($rol->nombre) }}
                                </option>
                            @endforeach
                        </select>
                        @error('rol_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Contraseña <span class="text-danger">*</span>
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
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            Confirmar Contraseña <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="form-control"
                               placeholder="Repita la contraseña"
                               required>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
