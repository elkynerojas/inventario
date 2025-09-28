<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Asignación - {{ $usuario->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            flex-direction: row;
            justify-content: flex-start;
            gap: 20px;
            width: 100%;
        }
        
        .logo-section {
            flex-shrink: 0;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        
        .logo-placeholder {
            width: 100px !important;
            height: 100px !important;
        }
        
        .title-section {
            text-align: left;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .title-section h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #333;
        }
        
        .title-section h2 {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #666;
        }
        
        .direccion {
            font-size: 9px;
            color: #666;
            margin: 0;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        
        .info-table .label {
            font-weight: bold;
            width: 30%;
        }
        
        .assets-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        .assets-table th,
        .assets-table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        
        .assets-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .assets-table .center {
            text-align: center;
        }
        
        .assets-table .right {
            text-align: right;
        }
        
        .observations {
            border: 1px solid #333;
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
        
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-direction: row;
            width: 100%;
            gap: 20px;
        }
        
        .signature-box {
            flex: 1;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 20px;
            min-width: 0;
        }
        
        .signature-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .signature-title {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .signature-date {
            font-size: 10px;
            color: #666;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                @if($colegio_logo && file_exists(public_path(str_replace(url('/'), '', $colegio_logo))))
                    <img src="{{ public_path(str_replace(url('/'), '', $colegio_logo)) }}" alt="Logo del Colegio" class="logo">
                @else
                    <div class="logo-placeholder" style="border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #666;">
                        Logo del Colegio
                    </div>
                @endif
            </div>
            <div class="title-section">
                <h1>{{ $colegio_nombre }}</h1>
                <h2>ACTA DE ASIGNACIÓN DE ACTIVOS</h2>
                <p class="direccion">{{ $colegio_direccion }}</p>
            </div>
        </div>
    </div>

    <!-- Información del Usuario -->
    <div class="section">
        <h3>Información del Usuario</h3>
        <table class="info-table">
            <tr>
                <td class="label">Nombre:</td>
                <td>{{ $usuario->name }}</td>
                <td class="label">Documento:</td>
                <td>{{ $usuario->documento }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td>{{ $usuario->email }}</td>
                <td class="label">Fecha del Acta:</td>
                <td>{{ \Carbon\Carbon::parse($fecha_acta)->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Resumen -->
    <div class="section">
        <h3>Resumen de Asignación</h3>
        <table class="info-table">
            <tr>
                <td class="label">Total de Activos:</td>
                <td>{{ $total_activos }}</td>
                <td class="label">Valor Total:</td>
                <td>${{ number_format($valor_total, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Lista de Activos -->
    <div class="section">
        <h3>Activos Asignados</h3>
        <table class="assets-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Código</th>
                    <th style="width: 25%;">Nombre</th>
                    <th style="width: 12%;">Marca</th>
                    <th style="width: 12%;">Modelo</th>
                    <th style="width: 12%;">Serial</th>
                    <th style="width: 8%;">Estado</th>
                    <th style="width: 10%;">Valor</th>
                    <th style="width: 9%;">Fecha Asignación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asignaciones as $asignacion)
                <tr>
                    <td class="center">{{ $asignacion->activo->codigo }}</td>
                    <td>{{ $asignacion->activo->nombre }}</td>
                    <td class="center">{{ $asignacion->activo->marca ?? 'N/A' }}</td>
                    <td class="center">{{ $asignacion->activo->modelo ?? 'N/A' }}</td>
                    <td class="center">{{ $asignacion->activo->serial ?? 'N/A' }}</td>
                    <td class="center">{{ ucfirst($asignacion->activo->estado) }}</td>
                    <td class="right">${{ number_format($asignacion->activo->valor_compra ?? 0, 2, ',', '.') }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="6" class="right">TOTAL:</td>
                    <td class="right">${{ number_format($valor_total, 2, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Observaciones -->
    <div class="section">
        <h3>Observaciones y Compromisos</h3>
        <div class="observations">
            <p><strong>El usuario {{ $usuario->name }} se compromete a:</strong></p>
            <ul>
                <li>Mantener los activos asignados en buen estado de conservación</li>
                <li>Reportar inmediatamente cualquier daño, pérdida o robo de los activos</li>
                <li>Devolver los activos cuando sea requerido por la empresa</li>
                <li>Cumplir con las políticas de uso y manejo de activos establecidas</li>
                <li>Permitir la verificación física de los activos cuando sea solicitada</li>
            </ul>
        </div>
    </div>

    <!-- Firmas -->
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-name">{{ $usuario->name }}</div>
            <div class="signature-title">Usuario Asignado</div>
            <div class="signature-date">C.C. {{ $usuario->documento }}</div>
        </div>
        
        <div class="signature-box">
            <div class="signature-name">{{ $firmado_por }}</div>
            <div class="signature-title">{{ $cargo_firmante }}</div>
            <div class="signature-date">Fecha: {{ \Carbon\Carbon::parse($fecha_acta)->format('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        <p>Este documento fue generado automáticamente el {{ date('d/m/Y H:i:s') }} por el Sistema de Inventario</p>
    </div>
</body>
</html>
