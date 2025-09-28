@extends('layouts.app')

@section('title', 'Editar Usuario - Sistema de Inventario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-person-gear"></i> Editar Usuario - {{ $usuario->name }}</h2>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Nombre Completo <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $usuario->name) }}"
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
                               value="{{ old('email', $usuario->email) }}"
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
                               value="{{ old('documento', $usuario->documento) }}"
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
                                <option value="{{ $rol->id }}" 
                                        {{ old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : '' }}>
                                    {{ ucfirst($rol->nombre) }}
                                </option>
                            @endforeach
                        </select>
                        @error('rol_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estado actual -->
                    <div class="mb-3">
                        <label class="form-label">Información del Usuario</label>
                        <div class="d-flex gap-3">
                            @if($usuario->rol)
                                <span class="badge 
                                    {{ $usuario->rol->nombre === 'admin' ? 'bg-danger' : 
                                       ($usuario->rol->nombre === 'profesor' ? 'bg-primary' : 'bg-success') }}">
                                    {{ ucfirst($usuario->rol->nombre) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h5 class="text-muted mb-2">Información Adicional</h5>
                        <div class="row text-sm">
                            <div class="col-md-6">
                                <span class="fw-bold">Activos asignados:</span> 
                                {{ $usuario->cantidadActivosAsignados() }}
                            </div>
                            <div class="col-md-6">
                                <span class="fw-bold">Fecha de registro:</span> 
                                {{ $usuario->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
