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
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 100)->unique(); // Clave única para identificar la configuración
            $table->string('categoria', 50)->default('general'); // Categoría: general, colegio, roles, sistema
            $table->string('nombre', 200); // Nombre descriptivo de la configuración
            $table->text('descripcion')->nullable(); // Descripción de qué hace esta configuración
            $table->text('valor')->nullable(); // Valor de la configuración (JSON para objetos complejos)
            $table->string('tipo', 20)->default('string'); // Tipo: string, number, boolean, json, file
            $table->json('opciones')->nullable(); // Opciones disponibles para selects
            $table->boolean('requerido')->default(false); // Si es requerido
            $table->boolean('activo')->default(true); // Si está activo
            $table->integer('orden')->default(0); // Orden de visualización
            $table->timestamps();
            
            // Índices
            $table->index(['categoria', 'activo']);
            $table->index(['clave']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
