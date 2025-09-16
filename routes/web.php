<?php

use App\Http\Controllers\ActivoController;
use App\Http\Controllers\AsignacionActivoController;
use App\Http\Controllers\BajaActivoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas de activos
Route::middleware(['auth'])->group(function () {
    Route::resource('activos', ActivoController::class);
    Route::get('activos/{activo}/estadisticas', [ActivoController::class, 'estadisticas'])->name('activos.estadisticas');
    
    // Rutas de asignaciones de activos
    Route::resource('asignaciones', AsignacionActivoController::class);
    Route::post('asignaciones/{asignacione}/devolver', [AsignacionActivoController::class, 'devolver'])->name('asignaciones.devolver');
    Route::post('asignaciones/{asignacione}/marcar-perdido', [AsignacionActivoController::class, 'marcarPerdido'])->name('asignaciones.marcar-perdido');
    Route::get('asignaciones/{asignacione}/acta', [AsignacionActivoController::class, 'generarActa'])->name('asignaciones.acta');
    Route::get('asignaciones/api/activos-disponibles', [AsignacionActivoController::class, 'activosDisponibles'])->name('asignaciones.activos-disponibles');
    Route::get('usuarios/{usuario}/asignaciones', [AsignacionActivoController::class, 'porUsuario'])->name('asignaciones.por-usuario');
    Route::get('activos/{activo}/historial-asignaciones', [AsignacionActivoController::class, 'historialActivo'])->name('asignaciones.historial-activo');

    // Rutas de bajas de activos
    Route::resource('bajas', BajaActivoController::class)->except(['edit', 'update', 'destroy']);
    Route::get('activos/{activo}/baja', [BajaActivoController::class, 'create'])->name('bajas.create');
    Route::post('activos/{activo}/baja', [BajaActivoController::class, 'store'])->name('bajas.store');
    Route::get('bajas/{baja}/acta', [BajaActivoController::class, 'generarActa'])->name('bajas.acta');
    
    // Rutas de reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/excel', [ReporteController::class, 'excel'])->name('reportes.excel');
    Route::get('reportes/pdf', [ReporteController::class, 'pdf'])->name('reportes.pdf');
    Route::get('reportes/estadisticas', [ReporteController::class, 'estadisticas'])->name('reportes.estadisticas');
    
    // Rutas de configuración (solo para administradores)
    Route::middleware(['auth'])->group(function () {
        Route::resource('configuraciones', ConfiguracionController::class);
        Route::post('configuraciones/categoria/{categoria}/actualizar', [ConfiguracionController::class, 'actualizarCategoria'])->name('configuraciones.actualizar-categoria');
        Route::post('configuraciones/restaurar-defecto', [ConfiguracionController::class, 'restaurarDefecto'])->name('configuraciones.restaurar-defecto');
        Route::get('configuraciones/exportar', [ConfiguracionController::class, 'exportar'])->name('configuraciones.exportar');
        Route::post('configuraciones/importar', [ConfiguracionController::class, 'importar'])->name('configuraciones.importar');
    });

    // Rutas de gestión de usuarios (solo para administradores)
    Route::middleware(['auth'])->group(function () {
        Route::resource('usuarios', UserController::class);
        Route::get('usuarios/{usuario}/reset-password', [UserController::class, 'showResetPassword'])->name('usuarios.reset-password');
        Route::patch('usuarios/{usuario}/reset-password', [UserController::class, 'resetPassword'])->name('usuarios.reset-password');
        
        // Rutas de gestión de roles
        Route::resource('roles', RolController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
