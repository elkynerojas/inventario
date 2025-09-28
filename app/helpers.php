<?php

if (!function_exists('configuracion')) {
    /**
     * Obtener una configuración del sistema
     *
     * @param string $clave
     * @param mixed $default
     * @return mixed
     */
    function configuracion($clave, $default = null)
    {
        return \App\Models\Configuracion::obtener($clave, $default);
    }
}

if (!function_exists('configuracion_establecer')) {
    /**
     * Establecer una configuración del sistema
     *
     * @param string $clave
     * @param mixed $valor
     * @param string $categoria
     * @return \App\Models\Configuracion
     */
    function configuracion_establecer($clave, $valor, $categoria = 'general')
    {
        return \App\Models\Configuracion::establecer($clave, $valor, $categoria);
    }
}

if (!function_exists('configuracion_categoria')) {
    /**
     * Obtener todas las configuraciones de una categoría
     *
     * @param string $categoria
     * @return \Illuminate\Support\Collection
     */
    function configuracion_categoria($categoria)
    {
        return \App\Models\Configuracion::obtenerPorCategoria($categoria);
    }
}

if (!function_exists('configuracion_todas')) {
    /**
     * Obtener todas las configuraciones del sistema
     *
     * @return \Illuminate\Support\Collection
     */
    function configuracion_todas()
    {
        return \App\Models\Configuracion::obtenerTodas();
    }
}

if (!function_exists('colegio_nombre')) {
    /**
     * Obtener el nombre del colegio
     *
     * @return string
     */
    function colegio_nombre()
    {
        return configuracion('nombre_colegio', 'INSTITUCIÓN EDUCATIVA');
    }
}

if (!function_exists('colegio_direccion')) {
    /**
     * Obtener la dirección del colegio
     *
     * @return string
     */
    function colegio_direccion()
    {
        return configuracion('direccion_colegio', 'Dirección no especificada');
    }
}

if (!function_exists('colegio_telefono')) {
    /**
     * Obtener el teléfono del colegio
     *
     * @return string
     */
    function colegio_telefono()
    {
        return configuracion('telefono_colegio', 'Teléfono no especificado');
    }
}

if (!function_exists('colegio_email')) {
    /**
     * Obtener el email del colegio
     *
     * @return string
     */
    function colegio_email()
    {
        return configuracion('email_colegio', 'email@colegio.com');
    }
}

if (!function_exists('colegio_logo')) {
    /**
     * Obtener la ruta del logo del colegio
     *
     * @return string
     */
    function colegio_logo()
    {
        return \App\Models\Configuracion::obtenerUrlLogo();
    }
}

if (!function_exists('sistema_version')) {
    /**
     * Obtener la versión del sistema
     *
     * @return string
     */
    function sistema_version()
    {
        return configuracion('version_sistema', '1.0.0');
    }
}

if (!function_exists('sistema_mantenimiento')) {
    /**
     * Verificar si el sistema está en modo mantenimiento
     *
     * @return bool
     */
    function sistema_mantenimiento()
    {
        return configuracion('modo_mantenimiento', false);
    }
}

if (!function_exists('reportes_formato_fecha')) {
    /**
     * Obtener el formato de fecha para reportes
     *
     * @return string
     */
    function reportes_formato_fecha()
    {
        return configuracion('formato_fecha_reportes', 'd/m/Y');
    }
}

if (!function_exists('reportes_items_por_pagina')) {
    /**
     * Obtener el número de items por página en reportes
     *
     * @return int
     */
    function reportes_items_por_pagina()
    {
        return configuracion('items_por_pagina_reportes', 20);
    }
}

if (!function_exists('email_smtp_host')) {
    /**
     * Obtener el host SMTP para emails
     *
     * @return string
     */
    function email_smtp_host()
    {
        return configuracion('email_smtp_host', 'smtp.gmail.com');
    }
}

if (!function_exists('email_smtp_puerto')) {
    /**
     * Obtener el puerto SMTP para emails
     *
     * @return int
     */
    function email_smtp_puerto()
    {
        return configuracion('email_smtp_puerto', 587);
    }
}

if (!function_exists('email_smtp_usuario')) {
    /**
     * Obtener el usuario SMTP para emails
     *
     * @return string
     */
    function email_smtp_usuario()
    {
        return configuracion('email_smtp_usuario', '');
    }
}

if (!function_exists('backup_frecuencia')) {
    /**
     * Obtener la frecuencia de respaldos
     *
     * @return string
     */
    function backup_frecuencia()
    {
        return configuracion('backup_frecuencia', 'diario');
    }
}

if (!function_exists('backup_retener_dias')) {
    /**
     * Obtener los días a retener respaldos
     *
     * @return int
     */
    function backup_retener_dias()
    {
        return configuracion('backup_retener_dias', 30);
    }
}

if (!function_exists('roles_administrador')) {
    /**
     * Obtener los roles de administrador
     *
     * @return array
     */
    function roles_administrador()
    {
        return configuracion('roles_administrador', ['admin', 'administrador']);
    }
}

if (!function_exists('roles_inventario')) {
    /**
     * Obtener los roles con acceso al inventario
     *
     * @return array
     */
    function roles_inventario()
    {
        return configuracion('roles_inventario', ['admin', 'administrador', 'inventario']);
    }
}

if (!function_exists('permisos_crear_activos')) {
    /**
     * Verificar si el usuario puede crear activos
     *
     * @return bool
     */
    function permisos_crear_activos()
    {
        return configuracion('permisos_crear_activos', true);
    }
}

if (!function_exists('permisos_eliminar_activos')) {
    /**
     * Verificar si el usuario puede eliminar activos
     *
     * @return bool
     */
    function permisos_eliminar_activos()
    {
        return configuracion('permisos_eliminar_activos', false);
    }
}

if (!function_exists('permisos_editar_configuracion')) {
    /**
     * Verificar si el usuario puede editar configuraciones
     *
     * @return bool
     */
    function permisos_editar_configuracion()
    {
        return configuracion('permisos_editar_configuracion', false);
    }
}
