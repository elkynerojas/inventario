<section>
    <header class="mb-4">
        <h3 class="h5 fw-bold text-dark">
            Actualizar Contraseña
        </h3>
        <p class="text-muted small mb-0">
            Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantener la seguridad.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-semibold">Contraseña Actual</label>
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                   autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-semibold">Nueva Contraseña</label>
            <input id="update_password_password" 
                   name="password" 
                   type="password" 
                   class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                   autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                   autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Guardar
            </button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-sm mb-0">
                    <i class="bi bi-check-circle me-1"></i>Guardado.
                </div>
            @endif
        </div>
    </form>
</section>
