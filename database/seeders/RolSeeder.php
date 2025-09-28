<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nombre' => 'admin'],
            ['nombre' => 'estudiante'],
            ['nombre' => 'profesor'],
            ['nombre' => 'rector(a)'],
            ['nombre' => 'administrativo'],
            ['nombre' => 'otro'],
           
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}