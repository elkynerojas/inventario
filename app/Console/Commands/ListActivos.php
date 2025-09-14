<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activo;

class ListActivos extends Command
{
    protected $signature = 'activos:list';
    protected $description = 'List all activos in the database';

    public function handle()
    {
        $activos = Activo::all();
        
        $this->info('Activos en la base de datos:');
        $this->newLine();
        
        foreach ($activos as $activo) {
            $this->line("• {$activo->codigo} - {$activo->nombre} ({$activo->estado}) - Valor: $" . number_format($activo->valor_compra, 2));
        }
        
        $this->newLine();
        $this->info("Total: {$activos->count()} activos");
    }
}