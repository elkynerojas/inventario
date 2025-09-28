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
        Schema::create('bajas_activos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_id')->constrained('activos')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_baja');
            $table->enum('motivo', ['obsoleto', 'dañado', 'perdido', 'vendido', 'donado', 'otro'])->default('otro');
            $table->text('descripcion_motivo');
            $table->decimal('valor_residual', 15, 2)->nullable();
            $table->string('destino', 200)->nullable(); // A dónde va el activo
            $table->text('observaciones')->nullable();
            $table->string('numero_acta', 50)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bajas_activos');
    }
};
