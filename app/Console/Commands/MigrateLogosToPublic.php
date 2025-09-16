<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Storage;

class MigrateLogosToPublic extends Command
{
    protected $signature = 'colegio:migrate-logos';
    protected $description = 'Migrar logos del colegio de storage a public/images/logos/';

    public function handle()
    {
        $this->info('=== MIGRANDO LOGOS DEL COLEGIO ===');
        
        // Buscar configuración del logo
        $config = Configuracion::where('clave', 'logo_colegio')->first();
        
        if (!$config) {
            $this->error('No se encontró configuración de logo_colegio');
            return;
        }
        
        $this->info("Configuración actual:");
        $this->info("- Valor: {$config->valor}");
        $this->info("- Tipo: {$config->tipo}");
        
        // Si ya está en public/images/logos/, no hacer nada
        if (str_starts_with($config->valor, 'images/logos/')) {
            $this->info('✓ El logo ya está en la nueva ubicación');
            return;
        }
        
        // Si está en storage, migrarlo
        if (str_starts_with($config->valor, 'configuraciones/')) {
            $this->migrateFromStorage($config);
        } else {
            $this->info('El logo no está en storage, no se requiere migración');
        }
        
        $this->info('=== MIGRACIÓN COMPLETADA ===');
    }
    
    private function migrateFromStorage(Configuracion $config)
    {
        $this->info('Migrando desde storage...');
        
        // Crear directorio si no existe
        $directorioLogos = public_path('images/logos');
        if (!file_exists($directorioLogos)) {
            mkdir($directorioLogos, 0755, true);
            $this->info('Directorio creado: ' . $directorioLogos);
        }
        
        // Verificar que el archivo existe en storage
        $rutaStorage = storage_path('app/public/' . $config->valor);
        if (!file_exists($rutaStorage)) {
            $this->error('Archivo no encontrado en storage: ' . $rutaStorage);
            return;
        }
        
        // Determinar extensión del archivo
        $extension = pathinfo($config->valor, PATHINFO_EXTENSION);
        $nuevoNombre = 'logo-colegio-actual.' . $extension;
        $nuevaRuta = $directorioLogos . '/' . $nuevoNombre;
        
        // Copiar archivo
        if (copy($rutaStorage, $nuevaRuta)) {
            $this->info('✓ Archivo copiado exitosamente');
            
            // Actualizar configuración
            $config->valor = 'images/logos/' . $nuevoNombre;
            $config->save();
            
            $this->info('✓ Configuración actualizada');
            
            // Limpiar cache
            Configuracion::limpiarCache();
            $this->info('✓ Cache limpiado');
            
            // Opcional: eliminar archivo de storage
            if ($this->confirm('¿Desea eliminar el archivo original de storage?')) {
                unlink($rutaStorage);
                $this->info('✓ Archivo original eliminado');
            }
        } else {
            $this->error('Error al copiar el archivo');
        }
    }
}
