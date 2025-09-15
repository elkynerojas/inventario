<?php

namespace App\Console\Commands;

use App\Models\AsignacionActivo;
use App\Models\Activo;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LimpiarAsignacionesInvalidas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asignaciones:limpiar-invalidas {--dry-run : Solo mostrar qué se eliminaría sin ejecutar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina asignaciones que referencian activos o usuarios inexistentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando asignaciones inválidas...');
        
        // Verificar asignaciones con activos faltantes
        $asignacionesSinActivo = AsignacionActivo::whereNotExists(function($query) {
            $query->select(DB::raw(1))
                  ->from('activos')
                  ->whereColumn('activos.id', 'asignaciones_activos.activo_id');
        })->get();

        // Verificar asignaciones con usuarios faltantes
        $asignacionesSinUsuario = AsignacionActivo::whereNotExists(function($query) {
            $query->select(DB::raw(1))
                  ->from('users')
                  ->whereColumn('users.id', 'asignaciones_activos.user_id');
        })->get();

        // Verificar asignaciones con asignador faltante
        $asignacionesSinAsignador = AsignacionActivo::whereNotExists(function($query) {
            $query->select(DB::raw(1))
                  ->from('users')
                  ->whereColumn('users.id', 'asignaciones_activos.asignado_por');
        })->get();

        $totalInvalidas = $asignacionesSinActivo->count() + $asignacionesSinUsuario->count() + $asignacionesSinAsignador->count();

        if ($totalInvalidas === 0) {
            $this->info('✅ No se encontraron asignaciones inválidas.');
            return;
        }

        $this->warn("⚠️  Se encontraron {$totalInvalidas} asignaciones inválidas:");
        
        if ($asignacionesSinActivo->count() > 0) {
            $this->line("   - {$asignacionesSinActivo->count()} asignaciones con activos faltantes");
            $this->line("     IDs: " . $asignacionesSinActivo->pluck('id')->implode(', '));
        }

        if ($asignacionesSinUsuario->count() > 0) {
            $this->line("   - {$asignacionesSinUsuario->count()} asignaciones con usuarios faltantes");
            $this->line("     IDs: " . $asignacionesSinUsuario->pluck('id')->implode(', '));
        }

        if ($asignacionesSinAsignador->count() > 0) {
            $this->line("   - {$asignacionesSinAsignador->count()} asignaciones con asignadores faltantes");
            $this->line("     IDs: " . $asignacionesSinAsignador->pluck('id')->implode(', '));
        }

        if ($this->option('dry-run')) {
            $this->info('🔍 Modo dry-run: No se eliminó nada.');
            return;
        }

        if ($this->confirm('¿Desea eliminar estas asignaciones inválidas?')) {
            $eliminadas = 0;
            
            if ($asignacionesSinActivo->count() > 0) {
                $eliminadas += $asignacionesSinActivo->count();
                AsignacionActivo::whereIn('id', $asignacionesSinActivo->pluck('id'))->delete();
            }

            if ($asignacionesSinUsuario->count() > 0) {
                $eliminadas += $asignacionesSinUsuario->count();
                AsignacionActivo::whereIn('id', $asignacionesSinUsuario->pluck('id'))->delete();
            }

            if ($asignacionesSinAsignador->count() > 0) {
                $eliminadas += $asignacionesSinAsignador->count();
                AsignacionActivo::whereIn('id', $asignacionesSinAsignador->pluck('id'))->delete();
            }

            $this->info("✅ Se eliminaron {$eliminadas} asignaciones inválidas.");
        } else {
            $this->info('❌ Operación cancelada.');
        }
    }
}
