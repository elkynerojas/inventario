<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportacionActivo extends Model
{
    use HasFactory;

    protected $table = 'importaciones_activos';

    protected $fillable = [
        'nombre_archivo',
        'ruta_archivo',
        'total_registros',
        'registros_procesados',
        'registros_exitosos',
        'registros_fallidos',
        'estado',
        'errores',
        'observaciones',
        'user_id',
    ];

    protected $casts = [
        'errores' => 'array',
        'observaciones' => 'array',
    ];

    /**
     * Relación con el usuario que realizó la importación
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Verificar si la importación está completada
     */
    public function estaCompletada(): bool
    {
        return $this->estado === 'completado';
    }

    /**
     * Verificar si la importación falló
     */
    public function fallo(): bool
    {
        return $this->estado === 'fallido';
    }

    /**
     * Verificar si la importación está en proceso
     */
    public function estaProcesando(): bool
    {
        return $this->estado === 'procesando';
    }

    /**
     * Obtener porcentaje de progreso
     */
    public function getPorcentajeProgresoAttribute(): float
    {
        if ($this->total_registros === 0 || $this->total_registros === null) {
            return 0;
        }
        return round(($this->registros_procesados / $this->total_registros) * 100, 2);
    }

    /**
     * Obtener estadísticas de la importación
     */
    public function getEstadisticasAttribute(): array
    {
        return [
            'total' => $this->total_registros,
            'procesados' => $this->registros_procesados,
            'exitosos' => $this->registros_exitosos,
            'fallidos' => $this->registros_fallidos,
            'porcentaje' => $this->porcentaje_progreso,
        ];
    }
}
