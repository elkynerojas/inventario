<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AyudaController extends Controller
{
    /**
     * Mostrar la página principal de ayuda
     */
    public function index(): View
    {
        return view('ayuda.index');
    }

    /**
     * Mostrar ayuda para asignaciones
     */
    public function asignaciones(): View
    {
        return view('ayuda.asignaciones');
    }

    /**
     * Mostrar ayuda para activos (solo administradores)
     */
    public function activos(): View
    {
        return view('ayuda.activos');
    }

    /**
     * Mostrar ayuda para reportes (solo administradores)
     */
    public function reportes(): View
    {
        return view('ayuda.reportes');
    }

    /**
     * Mostrar ayuda para usuarios (solo administradores)
     */
    public function usuarios(): View
    {
        return view('ayuda.usuarios');
    }

    /**
     * Mostrar ayuda para importaciones (solo administradores)
     */
    public function importaciones(): View
    {
        return view('ayuda.importaciones');
    }
}
