<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ActivosImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando importación de activos desde SQL...');
        
        // Ruta al archivo SQL
        $sqlFile = database_path('scripts/activos.sql');
        
        if (!File::exists($sqlFile)) {
            $this->command->error("Archivo SQL no encontrado: {$sqlFile}");
            return;
        }
        
        $this->command->info("Archivo SQL encontrado: {$sqlFile}");
        
        // Leer el archivo SQL completo
        $sqlContent = File::get($sqlFile);
        $this->command->info("Archivo SQL leído. Tamaño: " . strlen($sqlContent) . " caracteres");
        
        try {
            // Usar una aproximación más simple: ejecutar el SQL completo directamente
            // Primero, extraer solo las declaraciones INSERT completas
            
            // Dividir por declaraciones INSERT completas
            $insertStatements = $this->extractCompleteInsertStatements($sqlContent);
            
            $this->command->info("Encontradas " . count($insertStatements) . " declaraciones INSERT completas");
            
            $imported = 0;
            $errors = 0;
            
            foreach ($insertStatements as $index => $statement) {
                try {
                    $this->command->info("Ejecutando declaración " . ($index + 1) . "...");
                    
                    // Ejecutar la declaración SQL directamente
                    DB::statement($statement);
                    
                    // Contar registros insertados
                    $recordsInserted = $this->countRecordsInStatement($statement);
                    $imported += $recordsInserted;
                    
                    $this->command->info("Declaración " . ($index + 1) . " ejecutada exitosamente. Registros: {$recordsInserted}");
                    
                } catch (\Exception $e) {
                    $errors++;
                    $this->command->error("Error en declaración " . ($index + 1) . ": " . $e->getMessage());
                    $this->command->line("Declaración problemática: " . substr($statement, 0, 200) . "...");
                    continue;
                }
            }
            
            $finalCount = Activo::count();
            $this->command->info("Importación completada:");
            $this->command->info("- Registros procesados: {$imported}");
            $this->command->info("- Errores: {$errors}");
            $this->command->info("- Total de activos en la base de datos: {$finalCount}");
            
        } catch (\Exception $e) {
            $this->command->error("Error durante la importación: " . $e->getMessage());
        }
    }
    
    /**
     * Extraer declaraciones INSERT completas del contenido SQL
     */
    private function extractCompleteInsertStatements(string $sqlContent): array
    {
        $statements = [];
        
        // Buscar todas las declaraciones INSERT completas
        $pattern = '/INSERT INTO `activos` \([^)]+\) VALUES\s*[^;]+;/';
        preg_match_all($pattern, $sqlContent, $matches);
        
        foreach ($matches[0] as $match) {
            // Limpiar la declaración
            $statement = trim($match);
            if (!empty($statement)) {
                $statements[] = $statement;
            }
        }
        
        return $statements;
    }
    
    /**
     * Contar registros en una declaración INSERT
     */
    private function countRecordsInStatement(string $statement): int
    {
        // Buscar la parte VALUES
        $valuesPos = strpos($statement, 'VALUES');
        if ($valuesPos === false) {
            return 0;
        }
        
        $valuesPart = substr($statement, $valuesPos + 6);
        
        // Contar paréntesis de apertura (cada uno representa un registro)
        $count = substr_count($valuesPart, '(');
        
        return $count;
    }
}