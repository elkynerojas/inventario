<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Asignación de Activo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #dc3545;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #dc3545;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        
        .header h2 {
            color: #666;
            font-size: 18px;
            margin: 10px 0 0 0;
            font-weight: normal;
        }
        
        .empresa-info {
            text-align: center;
            margin-bottom: 30px;
            font-size: 10px;
            color: #666;
        }
        
        .acta-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .acta-info h3 {
            margin: 0 0 10px 0;
            color: #dc3545;
            font-size: 14px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
        }
        
        .activo-details {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .activo-details h4 {
            margin: 0 0 15px 0;
            color: #dc3545;
            font-size: 14px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .usuario-details {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .usuario-details h4 {
            margin: 0 0 15px 0;
            color: #dc3545;
            font-size: 14px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .firmas-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .firmas-grid {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .firma-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
            vertical-align: top;
        }
        
        .firma-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 40px;
        }
        
        .firma-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        
        .observaciones {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            min-height: 60px;
        }
        
        .observaciones h4 {
            margin: 0 0 10px 0;
            color: #dc3545;
            font-size: 14px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .estado-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .estado-activa {
            background-color: #28a745;
            color: white;
        }
        
        .estado-devuelta {
            background-color: #17a2b8;
            color: white;
        }
        
        .estado-perdida {
            background-color: #ffc107;
            color: #212529;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .firmas-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ACTA DE ASIGNACIÓN DE ACTIVO</h1>
        <h2>N° {{ $asignacion->id }}</h2>
    </div>
    
    <!-- Información de la empresa -->
    <div class="empresa-info">
        <strong>{{ $empresa['nombre'] }}</strong><br>
        {{ $empresa['direccion'] }}<br>
        Tel: {{ $empresa['telefono'] }} | Email: {{ $empresa['email'] }}
    </div>
    
    <!-- Información del acta -->
    <div class="acta-info">
        <h3>Información de la Asignación</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Fecha de Asignación:</div>
                <div class="info-value">{{ $asignacion->fecha_asignacion->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">
                    <span class="estado-badge estado-{{ $asignacion->estado }}">
                        {{ ucfirst($asignacion->estado) }}
                    </span>
                </div>
            </div>
            @if($asignacion->fecha_devolucion)
            <div class="info-row">
                <div class="info-label">Fecha de Devolución:</div>
                <div class="info-value">{{ $asignacion->fecha_devolucion->format('d/m/Y') }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Asignado por:</div>
                <div class="info-value">{{ $asignacion->asignadoPor->name }}</div>
            </div>
            @if($asignacion->ubicacion_asignada)
            <div class="info-row">
                <div class="info-label">Ubicación:</div>
                <div class="info-value">{{ $asignacion->ubicacion_asignada }}</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Detalles del Activo -->
    <div class="activo-details">
        <h4>Información del Activo</h4>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Código:</div>
                <div class="info-value"><strong>{{ $asignacion->activo->codigo }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value">{{ $asignacion->activo->nombre }}</div>
            </div>
            @if($asignacion->activo->marca)
            <div class="info-row">
                <div class="info-label">Marca:</div>
                <div class="info-value">{{ $asignacion->activo->marca }}</div>
            </div>
            @endif
            @if($asignacion->activo->modelo)
            <div class="info-row">
                <div class="info-label">Modelo:</div>
                <div class="info-value">{{ $asignacion->activo->modelo }}</div>
            </div>
            @endif
            @if($asignacion->activo->serial)
            <div class="info-row">
                <div class="info-label">Serial:</div>
                <div class="info-value">{{ $asignacion->activo->serial }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Estado del Activo:</div>
                <div class="info-value">{{ ucfirst($asignacion->activo->estado) }}</div>
            </div>
            @if($asignacion->activo->valor_compra)
            <div class="info-row">
                <div class="info-label">Valor de Compra:</div>
                <div class="info-value">${{ number_format($asignacion->activo->valor_compra, 2) }}</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Detalles del Usuario -->
    <div class="usuario-details">
        <h4>Información del Usuario Asignado</h4>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value"><strong>{{ $asignacion->usuario->name }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $asignacion->usuario->email }}</div>
            </div>
            @if($asignacion->usuario->documento)
            <div class="info-row">
                <div class="info-label">Documento:</div>
                <div class="info-value">{{ $asignacion->usuario->documento }}</div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Observaciones -->
    @if($asignacion->observaciones)
    <div class="observaciones">
        <h4>Observaciones</h4>
        <p>{{ $asignacion->observaciones }}</p>
    </div>
    @endif
    
    <!-- Sección de Firmas -->
    <div class="firmas-section">
        <div class="firmas-grid">
            <div class="firma-box">
                <div class="firma-line"></div>
                <div class="firma-label">Firma del Usuario Asignado</div>
                <div class="firma-label">{{ $asignacion->usuario->name }}</div>
                <div class="firma-label">C.C. {{ $asignacion->usuario->documento ?? 'N/A' }}</div>
            </div>
            <div class="firma-box">
                <div class="firma-line"></div>
                <div class="firma-label">Firma del Responsable</div>
                <div class="firma-label">{{ $asignacion->asignadoPor->name }}</div>
                <div class="firma-label">Fecha: _______________</div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Este documento fue generado automáticamente el {{ $fecha_generacion }}</p>
        <p>Sistema de Inventario - Todos los derechos reservados</p>
    </div>
</body>
</html>
