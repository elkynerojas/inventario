<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eliminar todas las configuraciones existentes
        Configuracion::truncate();

        $configuraciones = [
            // Configuraciones del Colegio
            [
                'clave' => 'nombre_colegio',
                'categoria' => 'colegio',
                'nombre' => 'Nombre del Colegio',
                'descripcion' => 'Nombre oficial de la institución educativa',
                'valor' => 'INSTITUCIÓN EDUCATIVA [NOMBRE]',
                'tipo' => 'string',
                'requerido' => true,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'clave' => 'direccion_colegio',
                'categoria' => 'colegio',
                'nombre' => 'Dirección del Colegio',
                'descripcion' => 'Dirección física de la institución',
                'valor' => 'Calle 123 #45-67, Ciudad',
                'tipo' => 'string',
                'requerido' => false,
                'activo' => true,
                'orden' => 2,
            ],
            [
                'clave' => 'telefono_colegio',
                'categoria' => 'colegio',
                'nombre' => 'Teléfono del Colegio',
                'descripcion' => 'Número telefónico de contacto',
                'valor' => '(601) 123-4567',
                'tipo' => 'string',
                'requerido' => false,
                'activo' => true,
                'orden' => 3,
            ],
            [
                'clave' => 'email_colegio',
                'categoria' => 'colegio',
                'nombre' => 'Email del Colegio',
                'descripcion' => 'Correo electrónico institucional',
                'valor' => 'contacto@colegio.edu.co',
                'tipo' => 'string',
                'requerido' => false,
                'activo' => true,
                'orden' => 4,
            ],
            [
                'clave' => 'logo_colegio',
                'categoria' => 'colegio',
                'nombre' => 'Logo del Colegio',
                'descripcion' => 'Ruta del archivo del logo institucional',
                'valor' => 'images/logos/logo-colegio.svg',
                'tipo' => 'file',
                'requerido' => false,
                'activo' => true,
                'orden' => 5,
            ],

            // Configuraciones del Sistema
            [
                'clave' => 'version_sistema',
                'categoria' => 'sistema',
                'nombre' => 'Versión del Sistema',
                'descripcion' => 'Versión actual del sistema de inventario',
                'valor' => '1.0.0',
                'tipo' => 'string',
                'requerido' => true,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'clave' => 'modo_mantenimiento',
                'categoria' => 'sistema',
                'nombre' => 'Modo Mantenimiento',
                'descripcion' => 'Activar modo de mantenimiento del sistema',
                'valor' => 'false',
                'tipo' => 'boolean',
                'requerido' => false,
                'activo' => true,
                'orden' => 2,
            ],

            // Configuraciones de Reportes
            [
                'clave' => 'formato_fecha_reportes',
                'categoria' => 'reportes',
                'nombre' => 'Formato de Fecha en Reportes',
                'descripcion' => 'Formato de fecha para mostrar en reportes',
                'valor' => 'd/m/Y',
                'tipo' => 'string',
                'opciones' => ['d/m/Y' => 'DD/MM/YYYY', 'Y-m-d' => 'YYYY-MM-DD', 'm/d/Y' => 'MM/DD/YYYY'],
                'requerido' => false,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'clave' => 'items_por_pagina_reportes',
                'categoria' => 'reportes',
                'nombre' => 'Items por Página en Reportes',
                'descripcion' => 'Número de elementos a mostrar por página en reportes',
                'valor' => '20',
                'tipo' => 'integer',
                'opciones' => ['10' => '10', '20' => '20', '50' => '50', '100' => '100'],
                'requerido' => false,
                'activo' => true,
                'orden' => 2,
            ],

            // Configuraciones de Roles y Permisos
            [
                'clave' => 'roles_administrador',
                'categoria' => 'roles',
                'nombre' => 'Roles de Administrador',
                'descripcion' => 'Roles que tienen permisos de administrador',
                'valor' => '["admin", "administrador"]',
                'tipo' => 'json',
                'requerido' => false,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'clave' => 'permisos_crear_activos',
                'categoria' => 'roles',
                'nombre' => 'Permiso Crear Activos',
                'descripcion' => 'Permitir crear nuevos activos en el sistema',
                'valor' => 'true',
                'tipo' => 'boolean',
                'requerido' => false,
                'activo' => true,
                'orden' => 2,
            ],
            [
                'clave' => 'permisos_eliminar_activos',
                'categoria' => 'roles',
                'nombre' => 'Permiso Eliminar Activos',
                'descripcion' => 'Permitir eliminar activos del sistema',
                'valor' => 'false',
                'tipo' => 'boolean',
                'requerido' => false,
                'activo' => true,
                'orden' => 3,
            ],
        ];


        // Eliminar todas las configuraciones existentes
        foreach ($configuraciones as $config) {
            Configuracion::updateOrCreate(
                ['clave' => $config['clave']],
                $config
            );
        }

        // Limpiar cache después de crear las configuraciones
        Configuracion::limpiarCache();
    }
}
