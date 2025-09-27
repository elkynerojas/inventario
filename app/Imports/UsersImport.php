<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    private $importedCount = 0;
    private $customErrors = [];

    public function model(array $row)
    {
        // Validar que los campos requeridos estén presentes
        if (empty($row['nombre']) || empty($row['email'])) {
            $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": Nombre y email son obligatorios";
            return null;
        }

        // Validar formato de email
        if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": Email inválido: " . $row['email'];
            return null;
        }

        // Verificar si el email ya existe
        if (User::where('email', $row['email'])->exists()) {
            $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": El email ya existe: " . $row['email'];
            return null;
        }

        // Verificar si el documento ya existe (si se proporciona)
        if (!empty($row['documento']) && User::where('documento', $row['documento'])->exists()) {
            $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": El documento ya existe: " . $row['documento'];
            return null;
        }

        // Obtener el rol
        $rol = null;
        if (!empty($row['rol'])) {
            $rol = Rol::where('nombre', strtolower(trim($row['rol'])))->first();
            if (!$rol) {
                $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": Rol no válido: " . $row['rol'];
                return null;
            }
        } else {
            // Rol por defecto: estudiante
            $rol = Rol::where('nombre', 'estudiante')->first();
        }

        // Generar contraseña por defecto si no se proporciona
        $password = !empty($row['password']) ? $row['password'] : 'password123';

        $this->importedCount++;

        return new User([
            'name' => trim($row['nombre']),
            'email' => trim($row['email']),
            'documento' => !empty($row['documento']) ? trim($row['documento']) : null,
            'password' => Hash::make($password),
            'rol_id' => $rol->id,
        ]);
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getErrors()
    {
        return $this->customErrors;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'documento' => 'nullable|string|max:20|unique:users,documento',
            'rol' => 'nullable|string|in:admin,estudiante,profesor',
            'password' => 'nullable|string|min:6',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe tener un formato válido',
            'email.unique' => 'El email ya existe en el sistema',
            'documento.unique' => 'El documento ya existe en el sistema',
            'rol.in' => 'El rol debe ser: admin, estudiante o profesor',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ];
    }
}
