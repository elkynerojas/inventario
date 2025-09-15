<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Baja de Activo</title>
    <link rel="stylesheet" href="{{ public_path('css/acta-baja.css') }}">
</head>
<body class="acta-baja">
    <div class="header">
        <h1>ACTA DE BAJA DE ACTIVO</h1>
        <h2>Sistema de Inventario</h2>
    </div>
    
    <div class="empresa-info">
        <strong>{{ $empresa['nombre'] }}</strong><br>
        {{ $empresa['direccion'] }}<br>
        Tel: {{ $empresa['telefono'] }} | Email: {{ $empresa['email'] }}<br>
        <strong>INSTITUCIÓN EDUCATIVA</strong>
    </div>
    
    <div class="acta-info">
        <h3>Información del Acta</h3>
        <div class="info-row">
            <span class="info-label">Número de Acta:</span>
            <span class="info-value"><strong>{{ $baja->numero_acta }}</strong></span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Baja:</span>
            <span class="info-value">{{ $baja->fecha_baja->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span class="info-value">{{ $fecha_generacion }}</span>
        </div>
    </div>
    
    <div class="info-grid">
        <div class="info-section">
            <h4>Información del Activo</h4>
            <div class="info-row">
                <span class="info-label">Código:</span>
                <span class="info-value">{{ $baja->activo->codigo }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span class="info-value">{{ $baja->activo->nombre }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Categoría:</span>
                <span class="info-value">{{ $baja->activo->grupo_articulo ?: 'Sin grupo' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Valor Original:</span>
                <span class="info-value">${{ number_format($baja->activo->valor_compra, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Fecha Adquisición:</span>
                <span class="info-value">{{ $baja->activo->fecha_compra ? $baja->activo->fecha_compra->format('d/m/Y') : 'No especificada' }}</span>
            </div>
        </div>
        
        <div class="info-section">
            <h4>Información de la Baja</h4>
            <div class="info-row">
                <span class="info-label">Motivo:</span>
                <span class="info-value">
                    <span class="badge badge-{{ $baja->motivo == 'obsoleto' ? 'warning' : ($baja->motivo == 'dañado' ? 'danger' : ($baja->motivo == 'perdido' ? 'info' : ($baja->motivo == 'vendido' ? 'success' : ($baja->motivo == 'donado' ? 'primary' : 'secondary')))) }}">
                        {{ $baja->motivo_formateado }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Valor Residual:</span>
                <span class="info-value">
                    @if($baja->tieneValorResidual())
                        ${{ number_format($baja->valor_residual, 2) }}
                    @else
                        No especificado
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Destino:</span>
                <span class="info-value">{{ $baja->destino ?: 'No especificado' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Procesado por:</span>
                <span class="info-value">{{ $baja->usuario->name }}</span>
            </div>
        </div>
    </div>
    
    <div class="motivo-section">
        <h4>Descripción del Motivo</h4>
        <div class="motivo-text">
            {{ $baja->descripcion_motivo }}
        </div>
    </div>
    
    @if($baja->observaciones)
        <div class="observaciones">
            <h4>Observaciones</h4>
            <div class="motivo-text">
                {{ $baja->observaciones }}
            </div>
        </div>
    @endif
    
    <div class="consideraciones">
        <h4>CONSIDERACIONES</h4>
        <div class="consideraciones-text">
            <p>Por medio del presente documento se certifica que el activo descrito anteriormente ha sido dado de baja del inventario institucional, conforme a las políticas y procedimientos establecidos por la institución educativa.</p>
            <p>Esta baja se realiza en cumplimiento de las normas contables y de control interno vigentes, garantizando la transparencia y trazabilidad de los procesos de gestión de activos.</p>
            <p>El presente acta tiene validez legal y debe ser archivada según los procedimientos establecidos para la documentación contable de la institución.</p>
        </div>
    </div>
    
    <div class="firmas">
        <div class="firmas-title">FIRMAS DE AUTORIZACIÓN</div>
        
        <div class="firmas-grid">
            <!-- Columna Izquierda -->
            <div class="columna-firmas">
                <div class="firma-section">
                    <div class="firma-line"></div>
                    <div class="firma-label">RECTOR</div>
                </div>
                <div class="firma-section">
                    <div class="firma-line"></div>
                    <div class="firma-label">REP. CONSEJO ACADÉMICO</div>
                </div>
                <div class="firma-section">
                    <div class="firma-line"></div>
                    <div class="firma-label">REP. CONSEJO DE DOCENTES</div>
                </div>
                <div class="firma-section">
                    <div class="firma-line"></div>
                    <div class="firma-label">REP. CONSEJO DE ESTUDIANTES</div>
                </div>
            </div>
            
            <!-- Columna Derecha -->
            <div class="columna-firmas">
                <div class="firma-section">
                    <div class="firma-line"></div>
                    <div class="firma-label">REP. CONSEJO DE PADRES</div>
                </div>
                <div class="firma-section">
                    <div class="firma-line"></div>
                    <div class="firma-label">REP. ASOCIACIÓN DE PADRES</div>
                </div>
                <div class="firma-section">
                    <div class="firma-line"></div>
                    <div class="firma-label">REP. EGRESADOS</div>
                </div>
                <div class="firma-section">
                    <div class="firma-line"></div>
                    <div class="firma-label">REP. SECTOR PRODUCTIVO</div>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <div class="firma-section">
                <div class="firma-line" style="width: 60%; margin: 0 auto;"></div>
                <div class="firma-label">RESPONSABLE DE INVENTARIO</div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>Este documento fue generado automáticamente el {{ $fecha_generacion }}</p>
        <p>Sistema de Inventario - {{ $empresa['nombre'] }}</p>
    </div>
</body>
</html>
