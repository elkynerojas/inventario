<?php

namespace App\Console\Commands;

use App\Models\AsignacionActivo;
use App\Models\Activo;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CrearDatosPrueba extends Command
{
    protected $signature = 'datos:crear-prueba';
    protected $description = 'Crea datos de prueba válidos para el sistema';

    public function handle()
    {
        $this->info('🚀 Creando datos de prueba...');

        DB::beginTransaction();
        try {
            // Crear roles si no existen
            $rolAdmin = Rol::firstOrCreate(['nombre' => 'admin']);
            $rolUsuario = Rol::firstOrCreate(['nombre' => 'usuario']);

            // Crear usuarios
            $admin = User::firstOrCreate(
                ['email' => 'admin@test.com'],
                [
                    'name' => 'Administrador',
                    'password' => bcrypt('password'),
                    'rol_id' => $rolAdmin->id,
                ]
            );

            $usuario = User::firstOrCreate(
                ['email' => 'usuario@test.com'],
                [
                    'name' => 'Usuario Prueba',
                    'password' => bcrypt('password'),
                    'rol_id' => $rolUsuario->id,
                ]
            );

            // Crear activos
            $activo1 = Activo::firstOrCreate(
                ['codigo' => 'ACT001'],
                [
                    'nombre' => 'Laptop Dell Inspiron',
                    'marca' => 'Dell',
                    'modelo' => 'Inspiron 15',
                    'serial' => 'DL001234',
                    'estado' => 'bueno',
                    'valor_compra' => 1500.00,
                    'fecha_compra' => now()->subDays(30),
                ]
            );

            $activo2 = Activo::firstOrCreate(
                ['codigo' => 'ACT002'],
                [
                    'nombre' => 'Monitor Samsung',
                    'marca' => 'Samsung',
                    'modelo' => '24" LED',
                    'serial' => 'SM002345',
                    'estado' => 'bueno',
                    'valor_compra' => 300.00,
                    'fecha_compra' => now()->subDays(15),
                ]
            );

            $activo3 = Activo::firstOrCreate(
                ['codigo' => 'ACT003'],
                [
                    'nombre' => 'Mouse Logitech',
                    'marca' => 'Logitech',
                    'modelo' => 'M705',
                    'serial' => 'LG003456',
                    'estado' => 'regular',
                    'valor_compra' => 50.00,
                    'fecha_compra' => now()->subDays(10),
                ]
            );

            // Limpiar asignaciones existentes
            AsignacionActivo::truncate();

            // Crear asignaciones válidas
            AsignacionActivo::create([
                'activo_id' => $activo1->id,
                'user_id' => $usuario->id,
                'asignado_por' => $admin->id,
                'fecha_asignacion' => now()->subDays(5),
                'estado' => 'activa',
                'observaciones' => 'Asignación para trabajo remoto',
                'ubicacion_asignada' => 'Oficina Principal',
            ]);

            AsignacionActivo::create([
                'activo_id' => $activo2->id,
                'user_id' => $usuario->id,
                'asignado_por' => $admin->id,
                'fecha_asignacion' => now()->subDays(20),
                'fecha_devolucion' => now()->subDays(2),
                'estado' => 'devuelta',
                'observaciones' => 'Asignación completada exitosamente',
                'ubicacion_asignada' => 'Laboratorio A',
            ]);

            DB::commit();

            $this->info('✅ Datos de prueba creados exitosamente:');
            $this->line("   - 2 usuarios creados");
            $this->line("   - 3 activos creados");
            $this->line("   - 2 asignaciones creadas");
            $this->line("   - Credenciales: admin@test.com / usuario@test.com (password: password)");

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('❌ Error al crear datos de prueba: ' . $e->getMessage());
        }
    }
}
