<x-guest-layout>
    <!-- Título del formulario -->
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-dark mb-2">Restablecer Contraseña</h2>
        <p class="text-muted small">Ingresa tu nueva contraseña</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
            <input id="email" 
                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   value="{{ old('email', $request->email) }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="tu@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Nueva Contraseña</label>
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

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                <i class="bi bi-key me-2"></i>Restablecer Contraseña
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