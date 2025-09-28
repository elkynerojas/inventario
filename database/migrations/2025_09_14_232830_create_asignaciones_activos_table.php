<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asignaciones_activos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_id')->constrained('activos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('asignado_por')->constrained('users')->onDelete('cascade');
            $table->date('fecha_asignacion');
            $table->date('fecha_devolucion')->nullable();
            $table->enum('estado', ['activa', 'devuelta', 'perdida'])->default('activa');
            $table->text('observaciones')->nullable();
            $table->string('ubicacion_asignada')->nullable();
            $table->timestamps();
            
            // Índice único para evitar asignaciones duplicadas activas
            $table->unique(['activo_id', 'user_id', 'estado'], 'unique_asignacion_activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones_activos');
    }
};
