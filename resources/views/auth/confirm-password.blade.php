<x-guest-layout>
    <!-- Título del formulario -->
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-dark mb-2">Confirmar Contraseña</h2>
        <p class="text-muted small">Esta es un área segura de la aplicación</p>
    </div>

    <div class="alert alert-warning mb-4">
        <i class="bi bi-shield-lock me-2"></i>
        Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Contraseña</label>
            <input id="password" 
                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                   type="password"
                   name="password"
                   required 
                   autocomplete="current-password"
                   placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                <i class="bi bi-check-circle me-2"></i>Confirmar
            </button>
        </div>
    </form>
</x-guest-layout>