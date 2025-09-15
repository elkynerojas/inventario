<?php

namespace Database\Seeders;

use App\Models\AsignacionActivo;
use App\Models\Activo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsignacionActivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunos usuarios si no existen
        if (User::count() == 0) {
            User::create([
                'name' => 'Admin Usuario',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
                'rol_id' => 1,
            ]);
            
            User::create([
                'name' => 'Usuario Prueba',
                'email' => 'usuario@test.com',
                'password' => bcrypt('password'),
                'rol_id' => 2,
            ]);
        }

        // Crear algunos activos si no existen
        if (Activo::count() == 0) {
            Activo::create([
                'codigo' => 'ACT001',
                'nombre' => 'Laptop Dell Inspiron',
                'marca' => 'Dell',
                'modelo' => 'Inspiron 15',
                'serial' => 'DL001234',
                'estado' => 'bueno',
                'valor_compra' => 1500.00,
                'fecha_compra' => now()->subDays(30),
            ]);

            Activo::create([
                'codigo' => 'ACT002',
                'nombre' => 'Monitor Samsung',
                'marca' => 'Samsung',
                'modelo' => '24" LED',
                'serial' => 'SM002345',
                'estado' => 'bueno',
                'valor_compra' => 300.00,
                'fecha_compra' => now()->subDays(15),
            ]);

            Activo::create([
                'codigo' => 'ACT003',
                'nombre' => 'Mouse Logitech',
                'marca' => 'Logitech',
                'modelo' => 'M705',
                'serial' => 'LG003456',
                'estado' => 'regular',
                'valor_compra' => 50.00,
                'fecha_compra' => now()->subDays(10),
            ]);
        }

        // Crear algunas asignaciones de prueba
        $usuarios = User::all();
        $activos = Activo::all();
        $admin = $usuarios->first();

        if ($usuarios->count() > 1 && $activos->count() > 0) {
            // Asignación activa
            AsignacionActivo::create([
                'activo_id' => $activos->first()->id,
                'user_id' => $usuarios->skip(1)->first()->id,
                'asignado_por' => $admin->id,
                'fecha_asignacion' => now()->subDays(5),
                'estado' => 'activa',
                'observaciones' => 'Asignación para trabajo remoto',
                'ubicacion_asignada' => 'Oficina Principal',
            ]);

            // Asignación devuelta
            if ($activos->count() > 1) {
                AsignacionActivo::create([
                    'activo_id' => $activos->skip(1)->first()->id,
                    'user_id' => $usuarios->skip(1)->first()->id,
                    'asignado_por' => $admin->id,
                    'fecha_asignacion' => now()->subDays(20),
                    'fecha_devolucion' => now()->subDays(2),
                    'estado' => 'devuelta',
                    'observaciones' => 'Asignación completada exitosamente',
                    'ubicacion_asignada' => 'Laboratorio A',
                ]);
            }
        }
    }
}
