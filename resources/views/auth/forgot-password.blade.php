<x-guest-layout>
    <!-- Título del formulario -->
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-dark mb-2">Recuperar Contraseña</h2>
        <p class="text-muted small">Ingresa tu correo electrónico para recibir un enlace de recuperación</p>
    </div>

    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle me-2"></i>
        ¿Olvidaste tu contraseña? No hay problema. Solo déjanos saber tu dirección de correo electrónico y te enviaremos un enlace de recuperación de contraseña que te permitirá elegir una nueva.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
            <input id="email" 
                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus
                   placeholder="tu@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                <i class="bi bi-envelope me-2"></i>Enviar Enlace de Recuperación
            </button>
        </div>
    </form>

    <!-- Enlaces adicionales -->
    <div class="text-center">
        <p class="text-muted small mb-0">
            ¿Recordaste tu contraseña? 
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                Inicia sesión aquí
            </a>
        </p>
    </div>
</x-guest-layout>