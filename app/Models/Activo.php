<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Activo extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'codigo_grupo_articulo',
        'codigo_grupo_contable',
        'tipo_bien',
        'codigo_servicio',
        'codigo_responsable',
        'nombre_responsable',
        'fecha_compra',
        'nro_compra',
        'vida_util',
        'estado',
        'modelo',
        'marca',
        'serial',
        'fecha_depreciacion',
        'valor_compra',
        'valor_depreciacion',
        'ubicacion',
        'recurso',
        'tipo_adquisicion',
        'observacion',
        'descripcion',
        'tipo_hoja_vi',
        'area_administrativa',
        'valor_residual',
        'fecha_creacion',
        'grupo_articulo',
        'fecha_depre',
        't_adquisicion',
        'tipo_hoja',
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'fecha_depreciacion' => 'date',
        'fecha_depre' => 'date',
        'fecha_creacion' => 'datetime',
        'valor_compra' => 'decimal:2',
        'valor_depreciacion' => 'decimal:2',
        'valor_residual' => 'decimal:2',
    ];

    /**
     * Scope para buscar en múltiples campos
     */
    public function scopeBuscar($query, $termino)
    {
        if (empty($termino)) {
            return $query;
        }

        return $query->where(function ($q) use ($termino) {
            $q->where('codigo', 'LIKE', "%{$termino}%")
              ->orWhere('nombre', 'LIKE', "%{$termino}%")
              ->orWhere('codigo_grupo_articulo', 'LIKE', "%{$termino}%")
              ->orWhere('codigo_grupo_contable', 'LIKE', "%{$termino}%")
              ->orWhere('tipo_bien', 'LIKE', "%{$termino}%")
              ->orWhere('codigo_servicio', 'LIKE', "%{$termino}%")
              ->orWhere('codigo_responsable', 'LIKE', "%{$termino}%")
              ->orWhere('nombre_responsable', 'LIKE', "%{$termino}%")
              ->orWhere('estado', 'LIKE', "%{$termino}%")
              ->orWhere('modelo', 'LIKE', "%{$termino}%")
              ->orWhere('marca', 'LIKE', "%{$termino}%")
              ->orWhere('serial', 'LIKE', "%{$termino}%")
              ->orWhere('ubicacion', 'LIKE', "%{$termino}%")
              ->orWhere('observacion', 'LIKE', "%{$termino}%")
              ->orWhere('descripcion', 'LIKE', "%{$termino}%");
        });
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por ubicación
     */
    public function scopePorUbicacion($query, $ubicacion)
    {
        return $query->where('ubicacion', 'LIKE', "%{$ubicacion}%");
    }

    /**
     * Scope para filtrar por responsable
     */
    public function scopePorResponsable($query, $responsable)
    {
        return $query->where('nombre_responsable', 'LIKE', "%{$responsable}%");
    }

    /**
     * Obtener estadísticas de activos
     */
    public static function obtenerEstadisticas(): array
    {
        $total = self::count();
        $porEstado = self::selectRaw('estado, COUNT(*) as cantidad')
            ->groupBy('estado')
            ->pluck('cantidad', 'estado')
            ->toArray();

        return [
            'total' => $total,
            'bueno' => $porEstado['bueno'] ?? 0,
            'regular' => $porEstado['regular'] ?? 0,
            'malo' => $porEstado['malo'] ?? 0,
            'dado_de_baja' => $porEstado['dado de baja'] ?? 0,
        ];
    }

    /**
     * Formatear valor de compra
     */
    public function getValorCompraFormateadoAttribute(): string
    {
        return '$' . number_format($this->valor_compra, 2);
    }

    /**
     * Verificar si está en buen estado
     */
    public function estaEnBuenEstado(): bool
    {
        return $this->estado === 'bueno';
    }

    /**
     * Verificar si está dado de baja
     */
    public function estaDadoDeBaja(): bool
    {
        return $this->estado === 'dado de baja';
    }

    /**
     * Relación con asignaciones
     */
    public function asignaciones()
    {
        return $this->hasMany(AsignacionActivo::class);
    }

    /**
     * Obtener asignación activa actual
     */
    public function asignacionActiva()
    {
        return $this->hasOne(AsignacionActivo::class)->activas();
    }

    /**
     * Obtener usuario actual asignado
     */
    public function usuarioAsignado()
    {
        return $this->hasOneThrough(
            User::class,
            AsignacionActivo::class,
            'activo_id',
            'id',
            'id',
            'user_id'
        )->where('asignaciones_activos.estado', 'activa');
    }

    /**
     * Verificar si el activo está asignado
     */
    public function estaAsignado(): bool
    {
        return $this->asignacionActiva()->exists();
    }

    /**
     * Verificar si el activo está disponible para asignación
     */
    public function estaDisponible(): bool
    {
        return !$this->estaAsignado() && !$this->estaDadoDeBaja();
    }

    /**
     * Obtener historial de asignaciones
     */
    public function historialAsignaciones()
    {
        return $this->asignaciones()->with(['usuario', 'asignadoPor'])->orderBy('fecha_asignacion', 'desc');
    }

    /**
     * Obtener estadísticas de asignaciones del activo
     */
    public function estadisticasAsignaciones(): array
    {
        $asignaciones = $this->asignaciones();
        
        return [
            'total_asignaciones' => $asignaciones->count(),
            'asignaciones_activas' => $asignaciones->activas()->count(),
            'asignaciones_devueltas' => $asignaciones->devueltas()->count(),
            'asignaciones_perdidas' => $asignaciones->perdidas()->count(),
            'esta_asignado' => $this->estaAsignado(),
            'esta_disponible' => $this->estaDisponible(),
        ];
    }
}