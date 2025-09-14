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
        Schema::create('activos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 200);
            $table->string('codigo_grupo_articulo', 50)->nullable();
            $table->string('codigo_grupo_contable', 50)->nullable();
            $table->string('tipo_bien', 10)->nullable();
            $table->string('codigo_servicio', 50)->nullable();
            $table->string('codigo_responsable', 50)->nullable();
            $table->string('nombre_responsable', 100)->nullable();
            $table->date('fecha_compra')->nullable();
            $table->string('nro_compra', 50)->nullable();
            $table->string('vida_util', 50)->nullable();
            $table->enum('estado', ['bueno', 'regular', 'malo', 'dado de baja'])->default('bueno');
            $table->string('modelo', 100)->nullable();
            $table->string('marca', 100)->nullable();
            $table->string('serial', 100)->nullable();
            $table->date('fecha_depreciacion')->nullable();
            $table->decimal('valor_compra', 15, 2)->default(0);
            $table->decimal('valor_depreciacion', 15, 2)->nullable();
            $table->string('ubicacion', 200)->nullable();
            $table->string('recurso', 200)->nullable();
            $table->string('tipo_adquisicion', 50)->nullable();
            $table->text('observacion')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo_hoja_vi', 50)->nullable();
            $table->string('area_administrativa', 100)->nullable();
            $table->decimal('valor_residual', 15, 2)->nullable();
            $table->timestamp('fecha_creacion')->nullable();
            $table->string('grupo_articulo', 100)->nullable();
            $table->date('fecha_depre')->nullable();
            $table->string('t_adquisicion', 50)->nullable();
            $table->string('tipo_hoja', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos');
    }
};
