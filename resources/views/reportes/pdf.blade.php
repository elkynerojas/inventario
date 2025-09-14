<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Activos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            text-align: center;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .info-item h3 {
            margin: 0 0 5px 0;
            color: #007bff;
            font-size: 14px;
        }
        
        .info-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        
        .filtro-info {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .filtro-info strong {
            color: #1976d2;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 7px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 2px;
            text-align: left;
        }
        
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9ecef;
        }
        
        .estado-bueno {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .estado-regular {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .estado-malo {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .estado-dado-de-baja {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .total-section {
            background-color: #28a745;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }
        
        .total-section h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        
        .total-value {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte de Inventario de Activos</h1>
        <p><strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}</p>
        <p><strong>Generado por:</strong> {{ auth()->user()->name ?? 'Sistema' }}</p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <h3>Total Activos</h3>
                <div class="value">{{ $estadisticas['total'] }}</div>
            </div>
            <div class="info-item">
                <h3>En Buen Estado</h3>
                <div class="value">{{ $estadisticas['bueno'] }}</div>
            </div>
            <div class="info-item">
                <h3>Estado Regular</h3>
                <div class="value">{{ $estadisticas['regular'] }}</div>
            </div>
            <div class="info-item">
                <h3>En Mal Estado</h3>
                <div class="value">{{ $estadisticas['malo'] }}</div>
            </div>
            <div class="info-item">
                <h3>Dados de Baja</h3>
                <div class="value">{{ $estadisticas['dado de baja'] ?? 0 }}</div>
            </div>
            <div class="info-item">
                <h3>Activos Reportados</h3>
                <div class="value">{{ $activos->count() }}</div>
            </div>
        </div>
    </div>

    <div class="filtro-info">
        <strong>Filtro aplicado:</strong> {{ $filtroAplicado }}
        @if($activos->count() >= 500)
        <br><small><strong>Nota:</strong> Se muestran los primeros 500 registros para optimizar el rendimiento del PDF.</small>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Ubicación</th>
                <th>Responsable</th>
                <th>Valor</th>
                <th>Fecha Compra</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Serial</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activos as $activo)
            <tr>
                <td>{{ $activo->id }}</td>
                <td>{{ $activo->codigo }}</td>
                <td>{{ Str::limit($activo->nombre, 30) }}</td>
                <td>
                    <span class="estado-{{ strtolower(str_replace(' ', '-', $activo->estado)) }}">
                        {{ ucfirst($activo->estado) }}
                    </span>
                </td>
                <td>{{ Str::limit($activo->ubicacion ?? 'N/A', 20) }}</td>
                <td>{{ Str::limit($activo->nombre_responsable ?? 'N/A', 20) }}</td>
                <td>${{ number_format($activo->valor_compra, 2) }}</td>
                <td>{{ $activo->fecha_compra ? $activo->fecha_compra->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ Str::limit($activo->marca ?? 'N/A', 15) }}</td>
                <td>{{ Str::limit($activo->modelo ?? 'N/A', 15) }}</td>
                <td>{{ Str::limit($activo->serial ?? 'N/A', 15) }}</td>
                <td>{{ Str::limit($activo->tipo_bien ?? 'N/A', 10) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <h3>💰 Valor Total de los Activos Reportados</h3>
        <div class="total-value">${{ number_format($totalValor, 2) }}</div>
    </div>

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el Sistema de Inventario de Activos</p>
        <p>Página generada el {{ date('d/m/Y') }} a las {{ date('H:i:s') }}</p>
    </div>
</body>
</html>
