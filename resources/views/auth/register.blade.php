<x-guest-layout>
    <!-- Título del formulario -->
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-dark mb-2">Crear Cuenta</h2>
        <p class="text-muted small">Regístrate para acceder al sistema</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nombre Completo</label>
            <input id="name" 
                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name"
                   placeholder="Tu nombre completo">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
            <input id="email" 
                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autocomplete="username"
                   placeholder="tu@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Contraseña</label>
            <input id="password" 
                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                   type="password"
                   name="password"
                   required 
                   autocomplete="new-password"
                   placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
            <input id="password_confirmation" 
                   class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                   type="password"
                   name="password_confirmation"
                   required 
                   autocomplete="new-password"
                   placeholder="••••••••">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                <i class="bi bi-person-plus me-2"></i>Crear Cuenta
            </button>
        </div>
    </form>

    <!-- Enlaces adicionales -->
    <div class="text-center">
        <p class="text-muted small mb-0">
            ¿Ya tienes una cuenta? 
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                Inicia sesión aquí
            </a>
        </p>
    </div>
</x-guest-layout>