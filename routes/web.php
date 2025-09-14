<?php

use App\Http\Controllers\ActivoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de activos
Route::middleware(['auth'])->group(function () {
    Route::resource('activos', ActivoController::class);
    Route::get('activos/{activo}/estadisticas', [ActivoController::class, 'estadisticas'])->name('activos.estadisticas');
    
    // Rutas de reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/excel', [ReporteController::class, 'excel'])->name('reportes.excel');
    Route::get('reportes/pdf', [ReporteController::class, 'pdf'])->name('reportes.pdf');
    Route::get('reportes/estadisticas', [ReporteController::class, 'estadisticas'])->name('reportes.estadisticas');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
