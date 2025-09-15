<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ActualizarDocumentosUsuarios extends Command
{
    protected $signature = 'usuarios:actualizar-documentos';
    protected $description = 'Actualiza usuarios existentes con números de documento de ejemplo';

    public function handle()
    {
        $this->info('🔄 Actualizando usuarios con números de documento...');

        $usuarios = User::whereNull('documento')->get();
        
        if ($usuarios->count() === 0) {
            $this->info('✅ Todos los usuarios ya tienen número de documento.');
            return;
        }

        $contador = 0;
        foreach ($usuarios as $usuario) {
            // Generar un número de documento único basado en el ID
            $documento = str_pad($usuario->id . rand(1000, 9999), 8, '0', STR_PAD_LEFT);
            
            $usuario->update(['documento' => $documento]);
            $contador++;
            
            $this->line("   - {$usuario->name} ({$usuario->email}): {$documento}");
        }

        $this->info("✅ Se actualizaron {$contador} usuarios con números de documento.");
    }
}
