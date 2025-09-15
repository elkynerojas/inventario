<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BajaActivo extends Model
{
    use HasFactory;

    protected $table = 'bajas_activos';

    protected $fillable = [
        'activo_id',
        'usuario_id',
        'fecha_baja',
        'motivo',
        'descripcion_motivo',
        'valor_residual',
        'destino',
        'observaciones',
        'numero_acta',
    ];

    protected $casts = [
        'fecha_baja' => 'date',
        'valor_residual' => 'decimal:2',
    ];

    /**
     * Relación con el activo
     */
    public function activo()
    {
        return $this->belongsTo(Activo::class);
    }

    /**
     * Relación con el usuario que procesó la baja
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generar número de acta único
     */
    public static function generarNumeroActa(): string
    {
        $year = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return "BA-{$year}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener motivo formateado
     */
    public function getMotivoFormateadoAttribute(): string
    {
        $motivos = [
            'obsoleto' => 'Obsoleto',
            'dañado' => 'Dañado',
            'perdido' => 'Perdido',
            'vendido' => 'Vendido',
            'donado' => 'Donado',
            'otro' => 'Otro',
        ];

        return $motivos[$this->motivo] ?? $this->motivo;
    }

    /**
     * Verificar si tiene valor residual
     */
    public function tieneValorResidual(): bool
    {
        return !is_null($this->valor_residual) && $this->valor_residual > 0;
    }

    /**
     * Obtener estadísticas de bajas
     */
    public static function obtenerEstadisticas(): array
    {
        $total = self::count();
        $porMotivo = self::selectRaw('motivo, COUNT(*) as cantidad')
            ->groupBy('motivo')
            ->pluck('cantidad', 'motivo')
            ->toArray();

        return [
            'total' => $total,
            'obsoleto' => $porMotivo['obsoleto'] ?? 0,
            'dañado' => $porMotivo['dañado'] ?? 0,
            'perdido' => $porMotivo['perdido'] ?? 0,
            'vendido' => $porMotivo['vendido'] ?? 0,
            'donado' => $porMotivo['donado'] ?? 0,
            'otro' => $porMotivo['otro'] ?? 0,
        ];
    }
}
