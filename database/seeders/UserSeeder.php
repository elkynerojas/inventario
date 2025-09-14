<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles
        $rolAdmin = Rol::where('nombre', 'admin')->first();
        $rolEstudiante = Rol::where('nombre', 'estudiante')->first();
        $rolProfesor = Rol::where('nombre', 'profesor')->first();

        // Crear usuarios de prueba
        $usuarios = [
            [
                'name' => 'Administrador',
                'email' => 'admin@inventario.com',
                'password' => Hash::make('admin123'),
                'rol_id' => $rolAdmin->id,
            ],
            [
                'name' => 'Estudiante',
                'email' => 'estudiante@inventario.com',
                'password' => Hash::make('estudiante123'),
                'rol_id' => $rolEstudiante->id,
            ],
            [
                'name' => 'Profesor',
                'email' => 'profesor@inventario.com',
                'password' => Hash::make('profesor123'),
                'rol_id' => $rolProfesor->id,
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}