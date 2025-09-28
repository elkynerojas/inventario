<?php

use App\Http\Controllers\ActivoController;
use App\Http\Controllers\ActaAsignacionController;
use App\Http\Controllers\AsignacionActivoController;
use App\Http\Controllers\AyudaController;
use App\Http\Controllers\BajaActivoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportacionActivoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->esAdmin()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('asignaciones.index');
        }
    }
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin.only'])
    ->name('dashboard');

// Rutas de asignaciones (acceso para todos los usuarios autenticados)
Route::middleware(['auth', 'user.permissions'])->group(function () {
    // Rutas de asignaciones de activos (solo lectura para usuarios no admin)
    Route::get('asignaciones', [AsignacionActivoController::class, 'index'])->name('asignaciones.index');
    Route::get('asignaciones/{asignacione}', [AsignacionActivoController::class, 'show'])->name('asignaciones.show');
});

// Rutas de asignaciones solo para administradores
Route::middleware(['auth', 'admin.only'])->group(function () {
    // Rutas de asignaciones de activos (crear, editar, eliminar)
    Route::get('asignaciones/create', [AsignacionActivoController::class, 'create'])->name('asignaciones.create');
    Route::post('asignaciones', [AsignacionActivoController::class, 'store'])->name('asignaciones.store');
    Route::get('asignaciones/{asignacione}/edit', [AsignacionActivoController::class, 'edit'])->name('asignaciones.edit');
    Route::put('asignaciones/{asignacione}', [AsignacionActivoController::class, 'update'])->name('asignaciones.update');
    Route::delete('asignaciones/{asignacione}', [AsignacionActivoController::class, 'destroy'])->name('asignaciones.destroy');
    
    // Acciones de asignaciones
    Route::post('asignaciones/{asignacione}/devolver', [AsignacionActivoController::class, 'devolver'])->name('asignaciones.devolver');
    Route::post('asignaciones/{asignacione}/marcar-perdido', [AsignacionActivoController::class, 'marcarPerdido'])->name('asignaciones.marcar-perdido');
    Route::get('asignaciones/{asignacione}/acta', [AsignacionActivoController::class, 'generarActa'])->name('asignaciones.acta');
    Route::get('asignaciones/api/activos-disponibles', [AsignacionActivoController::class, 'activosDisponibles'])->name('asignaciones.activos-disponibles');
    Route::get('usuarios/{usuario}/asignaciones', [AsignacionActivoController::class, 'porUsuario'])->name('asignaciones.por-usuario');
    Route::get('activos/{activo}/historial-asignaciones', [AsignacionActivoController::class, 'historialActivo'])->name('asignaciones.historial-activo');
});

// Rutas solo para administradores
Route::middleware(['auth', 'admin.only'])->group(function () {
    // Rutas de activos (solo administradores)
    Route::resource('activos', ActivoController::class);
    Route::get('activos/{activo}/estadisticas', [ActivoController::class, 'estadisticas'])->name('activos.estadisticas');

    // Rutas de bajas de activos (solo administradores)
    Route::get('activos/{activo}/baja', [BajaActivoController::class, 'create'])->name('activos.baja.create');
    Route::post('activos/{activo}/baja', [BajaActivoController::class, 'store'])->name('activos.baja.store');
    Route::resource('bajas', BajaActivoController::class)->except(['edit', 'update', 'destroy', 'create', 'store']);
    Route::get('bajas/{baja}/acta', [BajaActivoController::class, 'generarActa'])->name('bajas.acta');
    
    // Rutas de reportes (solo administradores)
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/excel', [ReporteController::class, 'excel'])->name('reportes.excel');
    Route::get('reportes/pdf', [ReporteController::class, 'pdf'])->name('reportes.pdf');
    Route::get('reportes/estadisticas', [ReporteController::class, 'estadisticas'])->name('reportes.estadisticas');
    
    // Rutas de importaciones de activos (solo administradores)
    Route::resource('importaciones', ImportacionActivoController::class)->except(['edit', 'update', 'destroy']);
    Route::get('importaciones/{importacion}/estado', [ImportacionActivoController::class, 'estado'])->name('importaciones.estado');
    Route::get('importaciones/plantilla/descargar', [ImportacionActivoController::class, 'plantilla'])->name('importaciones.plantilla');
    
    // Rutas de configuración (solo para administradores)
    Route::resource('configuraciones', ConfiguracionController::class);
    Route::post('configuraciones/categoria/{categoria}/actualizar', [ConfiguracionController::class, 'actualizarCategoria'])->name('configuraciones.actualizar-categoria');
    Route::post('configuraciones/restaurar-defecto', [ConfiguracionController::class, 'restaurarDefecto'])->name('configuraciones.restaurar-defecto');
    Route::get('configuraciones/exportar', [ConfiguracionController::class, 'exportar'])->name('configuraciones.exportar');
    Route::post('configuraciones/importar', [ConfiguracionController::class, 'importar'])->name('configuraciones.importar');

    // Rutas de gestión de usuarios (solo para administradores)
    // Rutas específicas ANTES que resource (para evitar conflictos con {usuario})
    Route::get('usuarios/importar', [UserController::class, 'showImport'])->name('usuarios.import');
    Route::post('usuarios/importar', [UserController::class, 'import'])->name('usuarios.import');
    Route::get('usuarios/plantilla', [UserController::class, 'downloadTemplate'])->name('usuarios.template');
    Route::get('usuarios/{usuario}/reset-password', [UserController::class, 'showResetPassword'])->name('usuarios.reset-password');
    Route::patch('usuarios/{usuario}/reset-password', [UserController::class, 'resetPassword'])->name('usuarios.reset-password');
    
    // Resource routes
    Route::resource('usuarios', UserController::class);
    
    // Rutas de gestión de roles
    Route::resource('roles', RolController::class);
    
    // Rutas para actas de asignación (solo administradores)
    Route::get('actas', [ActaAsignacionController::class, 'index'])->name('actas.index');
    Route::get('actas/vista-previa', [ActaAsignacionController::class, 'vistaPrevia'])->name('actas.vista-previa');
    Route::get('actas/generar', [ActaAsignacionController::class, 'generar'])->name('actas.generar');
    
    // Rutas de ayuda para administradores
    Route::get('/ayuda/activos', [AyudaController::class, 'activos'])->name('ayuda.activos');
    Route::get('/ayuda/reportes', [AyudaController::class, 'reportes'])->name('ayuda.reportes');
    Route::get('/ayuda/usuarios', [AyudaController::class, 'usuarios'])->name('ayuda.usuarios');
    Route::get('/ayuda/importaciones', [AyudaController::class, 'importaciones'])->name('ayuda.importaciones');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Rutas de ayuda
    Route::get('/ayuda', [AyudaController::class, 'index'])->name('ayuda.index');
    Route::get('/ayuda/asignaciones', [AyudaController::class, 'asignaciones'])->name('ayuda.asignaciones');
});

// Rutas de perfil solo para administradores
Route::middleware(['auth', 'admin.only'])->group(function () {
    Route::patch('/profile/info', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
