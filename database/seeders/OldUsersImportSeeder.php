<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class OldUsersImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles existentes
        $rolAdmin = Rol::where('nombre', 'admin')->first();
        $rolEstudiante = Rol::where('nombre', 'estudiante')->first();
        $rolProfesor = Rol::where('nombre', 'profesor')->first();

        // Datos de usuarios del sistema original
        // NOTA: Los hashes originales están truncados, por lo que generamos nuevos hashes con contraseñas conocidas
        $usuariosOriginales = [
            [
                'id' => 1,
                'name' => 'Administrador del Sistema',
                'email' => 'admin@demo.com',
                'password' => 'admin123', // Contraseña conocida para regenerar hash
                'rol_id' => $rolAdmin->id,
                'created_at' => '2025-09-01 22:05:13',
            ],
            [
                'id' => 2,
                'name' => 'admin123',
                'email' => 'admin@gmail.com',
                'password' => 'admin123', // Contraseña conocida para regenerar hash
                'rol_id' => $rolAdmin->id,
                'created_at' => '2025-09-01 22:07:12',
            ],
            [
                'id' => 3,
                'name' => 'Estudiante Demo',
                'email' => 'estudiante@demo.com',
                'password' => 'estudiante123', // Contraseña conocida para regenerar hash
                'rol_id' => $rolEstudiante->id,
                'created_at' => '2025-09-01 22:07:49',
            ],
            [
                'id' => 4,
                'name' => 'Profesor Demo',
                'email' => 'profesor@demo.com',
                'password' => 'profesor123', // Contraseña conocida para regenerar hash
                'rol_id' => $rolProfesor->id,
                'created_at' => '2025-09-01 22:07:49',
            ],
        ];

        foreach ($usuariosOriginales as $usuarioData) {
            // Verificar si el usuario ya existe
            $usuarioExistente = User::where('email', $usuarioData['email'])->first();
            
            if (!$usuarioExistente) {
                // Crear nuevo usuario con hash regenerado
                User::create([
                    'name' => $usuarioData['name'],
                    'email' => $usuarioData['email'],
                    'password' => Hash::make($usuarioData['password']), // Generar nuevo hash
                    'rol_id' => $usuarioData['rol_id'],
                    'created_at' => $usuarioData['created_at'],
                    'updated_at' => $usuarioData['created_at'],
                ]);
                
                $this->command->info("Usuario importado: {$usuarioData['name']} ({$usuarioData['email']}) - Contraseña: {$usuarioData['password']}");
            } else {
                $this->command->warn("Usuario ya existe: {$usuarioData['email']}");
            }
        }

        $this->command->info('Importación de usuarios completada.');
        $this->command->info('NOTA: Se generaron nuevos hashes de contraseña ya que los originales estaban truncados.');
    }
}