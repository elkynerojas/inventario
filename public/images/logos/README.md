# Logos del Colegio

Este directorio contiene los logos del colegio que se pueden configurar desde el módulo de configuración del sistema.

## Cómo cambiar el logo del colegio

1. **Acceder al módulo de configuración:**
   - Iniciar sesión como administrador
   - Ir al menú "Configuración"

2. **Buscar la configuración del logo:**
   - Filtrar por categoría "Colegio"
   - Buscar "Logo del Colegio"

3. **Subir nuevo logo:**
   - Hacer clic en "Editar" en la configuración del logo
   - Seleccionar un nuevo archivo de imagen
   - Guardar los cambios

## Formatos soportados

- **PNG** (recomendado)
- **JPG/JPEG**
- **SVG**
- **GIF**

## Recomendaciones

- **Tamaño:** Máximo 200x200 píxeles
- **Formato:** PNG con fondo transparente
- **Nombre:** Usar nombres descriptivos como `logo-colegio.png`

## Estructura de archivos

```
public/images/logos/
├── logo-colegio.png          # Logo principal del colegio
├── logo-colegio-alt.png      # Logo alternativo (opcional)
└── README.md                 # Este archivo
```

## Notas técnicas

- Los logos se almacenan en `public/images/logos/`
- El sistema verifica automáticamente si el archivo existe
- Si no hay logo configurado, se muestra un icono placeholder
- Los cambios se aplican inmediatamente sin necesidad de reiniciar el servidor
