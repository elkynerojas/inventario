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
        if (empty($row['nombre']) || empty($row['email']) || empty($row['documento'])) {
            $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": Nombre, email y documento son obligatorios";
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

        // Validar formato del documento (solo números y letras, sin espacios)
        $documento = trim($row['documento']);
        if (!preg_match('/^[a-zA-Z0-9]+$/', $documento)) {
            $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": El documento debe contener solo números y letras (sin espacios): " . $row['documento'];
            return null;
        }

        // Verificar longitud del documento
        if (strlen($documento) < 5 || strlen($documento) > 20) {
            $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": El documento debe tener entre 5 y 20 caracteres: " . $row['documento'];
            return null;
        }

        // Verificar si el documento ya existe
        $usuarioExistente = User::where('documento', $documento)->first();
        if ($usuarioExistente) {
            $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": El documento '{$documento}' ya existe (usuario: {$usuarioExistente->name} - {$usuarioExistente->email})";
            return null;
        }

        // Obtener el rol
        $rol = null;
        if (!empty($row['rol'])) {
            $rolNombre = strtolower(trim($row['rol']));
            $rol = Rol::where('nombre', $rolNombre)->first();
            if (!$rol) {
                // Obtener roles válidos para el mensaje de error
                $rolesValidos = Rol::pluck('nombre')->implode(', ');
                $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": Rol no válido: '{$row['rol']}'. Roles válidos: {$rolesValidos}";
                return null;
            }
        } else {
            // Rol por defecto: estudiante
            $rol = Rol::where('nombre', 'estudiante')->first();
            if (!$rol) {
                $this->customErrors[] = "Fila " . ($this->importedCount + 2) . ": No se encontró el rol por defecto 'estudiante' en la base de datos";
                return null;
            }
        }

        // Generar contraseña por defecto si no se proporciona (usar el documento)
        $password = !empty($row['password']) ? $row['password'] : $documento;

        $this->importedCount++;

        return new User([
            'name' => trim($row['nombre']),
            'email' => trim($row['email']),
            'documento' => $documento,
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
        // Obtener roles válidos de la base de datos
        $rolesValidos = Rol::pluck('nombre')->implode(',');
        
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'documento' => 'required|string|min:5|max:20|regex:/^[a-zA-Z0-9]+$/|unique:users,documento',
            'rol' => 'nullable|string|in:' . $rolesValidos,
            'password' => 'nullable|string|min:6',
        ];
    }

    public function customValidationMessages()
    {
        // Obtener roles válidos de la base de datos
        $rolesValidos = Rol::pluck('nombre')->implode(', ');
        
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe tener un formato válido',
            'email.unique' => 'El email ya existe en el sistema',
            'documento.required' => 'El documento es obligatorio',
            'documento.min' => 'El documento debe tener al menos 5 caracteres',
            'documento.max' => 'El documento no puede tener más de 20 caracteres',
            'documento.regex' => 'El documento debe contener solo números y letras (sin espacios)',
            'documento.unique' => 'El documento ya existe en el sistema. Debe ser único.',
            'rol.in' => 'El rol debe ser uno de los siguientes: ' . $rolesValidos,
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ];
    }
}
