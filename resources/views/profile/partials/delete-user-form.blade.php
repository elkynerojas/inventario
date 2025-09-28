<section>
    <header class="mb-4">
        <h3 class="h5 fw-bold text-dark">
            Eliminar Cuenta
        </h3>
        <p class="text-muted small mb-0">
            Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, descarga cualquier dato o información que desees conservar.
        </p>
    </header>

    <button type="button" 
            class="btn btn-danger" 
            data-bs-toggle="modal" 
            data-bs-target="#confirmUserDeletion">
        <i class="bi bi-trash me-2"></i>Eliminar Cuenta
    </button>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUserDeletionLabel">
                        ¿Estás seguro de que quieres eliminar tu cuenta?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-body">
                        <p class="text-muted small">
                            Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                   placeholder="Ingresa tu contraseña"
                                   required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Eliminar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
