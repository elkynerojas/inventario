@extends('layouts.app')

@section('title', 'Perfil de Usuario - Sistema de Inventario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="mb-0"><i class="bi bi-person-circle"></i> Perfil de Usuario</h2>
            </div>
        </div>

        @if(auth()->user()->esAdmin())
        <!-- Información del Perfil -->
        <div class="card mb-4">
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
        @endif

        <!-- Cambiar Contraseña -->
        <div class="card mb-4">
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        @if(auth()->user()->esAdmin())
        <!-- Eliminar Cuenta -->
        <div class="card">
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
