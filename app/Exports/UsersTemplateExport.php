<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                'Juan Pérez',
                'juan.perez@email.com',
                '12345678',
                'estudiante',
                'mipassword123'
            ],
            [
                'María García',
                'maria.garcia@email.com',
                '87654321',
                'profesor',
                ''
            ],
            [
                'Carlos López',
                'carlos.lopez@email.com',
                '',
                'admin',
                'admin123'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nombre',
            'email',
            'documento',
            'rol',
            'password'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para los encabezados
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '366092']
                ]
            ],
        ];
    }
}
