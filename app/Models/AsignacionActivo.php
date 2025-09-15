<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AsignacionActivo extends Model
{
    use HasFactory;

    protected $table = 'asignaciones_activos';

    protected $fillable = [
        'activo_id',
        'user_id',
        'asignado_por',
        'fecha_asignacion',
        'fecha_devolucion',
        'estado',
        'observaciones',
        'ubicacion_asignada',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_devolucion' => 'date',
    ];

    /**
     * Relación con el activo
     */
    public function activo()
    {
        return $this->belongsTo(Activo::class);
    }

    /**
     * Relación con el usuario asignado
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el usuario que realizó la asignación
     */
    public function asignadoPor()
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope para asignaciones devueltas
     */
    public function scopeDevueltas($query)
    {
        return $query->where('estado', 'devuelta');
    }

    /**
     * Scope para asignaciones perdidas
     */
    public function scopePerdidas($query)
    {
        return $query->where('estado', 'perdida');
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopePorUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por activo
     */
    public function scopePorActivo($query, $activoId)
    {
        return $query->where('activo_id', $activoId);
    }

    /**
     * Verificar si la asignación está activa
     */
    public function estaActiva(): bool
    {
        return $this->estado === 'activa';
    }

    /**
     * Verificar si la asignación fue devuelta
     */
    public function fueDevuelta(): bool
    {
        return $this->estado === 'devuelta';
    }

    /**
     * Verificar si la asignación está perdida
     */
    public function estaPerdida(): bool
    {
        return $this->estado === 'perdida';
    }

    /**
     * Marcar como devuelta
     */
    public function marcarComoDevuelta($fechaDevolucion = null, $observaciones = null)
    {
        $this->update([
            'estado' => 'devuelta',
            'fecha_devolucion' => $fechaDevolucion ?? now()->toDateString(),
            'observaciones' => $observaciones ?? $this->observaciones,
        ]);
    }

    /**
     * Marcar como perdida
     */
    public function marcarComoPerdida($observaciones = null)
    {
        $this->update([
            'estado' => 'perdida',
            'observaciones' => $observaciones ?? $this->observaciones,
        ]);
    }

    /**
     * Obtener duración de la asignación en días
     */
    public function getDuracionEnDiasAttribute(): int
    {
        $fechaFin = $this->fecha_devolucion ?? now();
        return $this->fecha_asignacion->diffInDays($fechaFin);
    }

    /**
     * Obtener estadísticas de asignaciones
     */
    public static function obtenerEstadisticas(): array
    {
        $total = self::count();
        $activas = self::activas()->count();
        $devueltas = self::devueltas()->count();
        $perdidas = self::perdidas()->count();

        return [
            'total' => $total,
            'activas' => $activas,
            'devueltas' => $devueltas,
            'perdidas' => $perdidas,
        ];
    }
}
