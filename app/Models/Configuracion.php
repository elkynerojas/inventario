<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'categoria',
        'nombre',
        'descripcion',
        'valor',
        'tipo',
        'opciones',
        'requerido',
        'activo',
        'orden',
    ];

    protected $casts = [
        'opciones' => 'array',
        'requerido' => 'boolean',
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Obtener una configuración por su clave
     */
    public static function obtener($clave, $default = null)
    {
        return Cache::remember("configuracion.{$clave}", 3600, function () use ($clave, $default) {
            $config = self::where('clave', $clave)
                ->where('activo', true)
                ->first();
            
            if (!$config) {
                return $default;
            }

            return self::convertirValor($config->valor, $config->tipo);
        });
    }

    /**
     * Establecer una configuración
     */
    public static function establecer($clave, $valor, $categoria = 'general')
    {
        $config = self::where('clave', $clave)->first();
        
        if (!$config) {
            $config = self::create([
                'clave' => $clave,
                'categoria' => $categoria,
                'nombre' => ucfirst(str_replace('_', ' ', $clave)),
                'tipo' => self::detectarTipo($valor),
                'valor' => self::convertirValorParaGuardar($valor),
            ]);
        } else {
            $config->update([
                'valor' => self::convertirValorParaGuardar($valor),
            ]);
        }

        // Limpiar cache
        Cache::forget("configuracion.{$clave}");
        
        return $config;
    }

    /**
     * Obtener todas las configuraciones de una categoría
     */
    public static function obtenerPorCategoria($categoria)
    {
        return Cache::remember("configuracion.categoria.{$categoria}", 3600, function () use ($categoria) {
            return self::where('categoria', $categoria)
                ->where('activo', true)
                ->orderBy('orden')
                ->get()
                ->mapWithKeys(function ($config) {
                    return [$config->clave => self::convertirValor($config->valor, $config->tipo)];
                });
        });
    }

    /**
     * Obtener todas las configuraciones como array asociativo
     */
    public static function obtenerTodas()
    {
        return Cache::remember('configuracion.todas', 3600, function () {
            return self::where('activo', true)
                ->orderBy('categoria')
                ->orderBy('orden')
                ->get()
                ->mapWithKeys(function ($config) {
                    return [$config->clave => self::convertirValor($config->valor, $config->tipo)];
                });
        });
    }

    /**
     * Convertir valor según el tipo
     */
    private static function convertirValor($valor, $tipo)
    {
        if (is_null($valor)) {
            return null;
        }

        switch ($tipo) {
            case 'boolean':
                return filter_var($valor, FILTER_VALIDATE_BOOLEAN);
            case 'number':
            case 'integer':
                return is_numeric($valor) ? (int) $valor : 0;
            case 'float':
                return is_numeric($valor) ? (float) $valor : 0.0;
            case 'json':
                return is_string($valor) ? json_decode($valor, true) : $valor;
            default:
                return $valor;
        }
    }

    /**
     * Convertir valor para guardar en base de datos
     */
    private static function convertirValorParaGuardar($valor)
    {
        if (is_array($valor) || is_object($valor)) {
            return json_encode($valor);
        }
        
        return $valor;
    }

    /**
     * Detectar tipo de valor automáticamente
     */
    private static function detectarTipo($valor)
    {
        if (is_bool($valor)) {
            return 'boolean';
        } elseif (is_int($valor)) {
            return 'integer';
        } elseif (is_float($valor)) {
            return 'float';
        } elseif (is_array($valor) || is_object($valor)) {
            return 'json';
        } else {
            return 'string';
        }
    }

    /**
     * Limpiar cache de configuraciones
     */
    public static function limpiarCache()
    {
        Cache::forget('configuracion.todas');
        
        // Limpiar cache por categorías
        $categorias = self::select('categoria')->distinct()->pluck('categoria');
        foreach ($categorias as $categoria) {
            Cache::forget("configuracion.categoria.{$categoria}");
        }
        
        // Limpiar cache individual
        $claves = self::select('clave')->pluck('clave');
        foreach ($claves as $clave) {
            Cache::forget("configuracion.{$clave}");
        }
    }

    /**
     * Scope para configuraciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para configuraciones por categoría
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para configuraciones requeridas
     */
    public function scopeRequeridas($query)
    {
        return $query->where('requerido', true);
    }

    /**
     * Accessor para obtener el valor convertido
     */
    public function getValorConvertidoAttribute()
    {
        return self::convertirValor($this->valor, $this->tipo);
    }

    /**
     * Accessor para obtener las opciones como array
     */
    public function getOpcionesArrayAttribute()
    {
        return $this->opciones ?? [];
    }

    /**
     * Verificar si la configuración es válida
     */
    public function esValida()
    {
        if ($this->requerido && empty($this->valor)) {
            return false;
        }

        // Validaciones específicas por tipo
        switch ($this->tipo) {
            case 'number':
            case 'integer':
            case 'float':
                return is_numeric($this->valor);
            case 'boolean':
                return in_array($this->valor, ['true', 'false', '1', '0', true, false]);
            case 'json':
                if (is_string($this->valor)) {
                    json_decode($this->valor);
                    return json_last_error() === JSON_ERROR_NONE;
                }
                return true;
            default:
                return true;
        }
    }
}