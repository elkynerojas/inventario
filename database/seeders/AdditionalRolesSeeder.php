<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class AdditionalRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $additionalRoles = [
            ['nombre' => 'coordinador'],
            ['nombre' => 'secretario'],
            ['nombre' => 'bibliotecario'],
            ['nombre' => 'mantenimiento'],
        ];

        foreach ($additionalRoles as $rol) {
            // Solo crear el rol si no existe
            Rol::firstOrCreate(['nombre' => $rol['nombre']], $rol);
        }
    }
}
