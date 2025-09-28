# Módulo de Gestión de Usuarios

Este módulo proporciona una interfaz completa para la gestión de usuarios en el sistema de inventario, incluyendo la creación, edición, eliminación y administración de roles.

## Funcionalidades Implementadas

### 🔐 Gestión de Usuarios
- **Listar usuarios** con filtros de búsqueda por nombre, email o documento
- **Crear nuevos usuarios** con validación completa de datos
- **Editar usuarios existentes** manteniendo la integridad de los datos
- **Eliminar usuarios** con validaciones de seguridad
- **Ver detalles completos** de cada usuario
- **Restablecer contraseñas** de usuarios

### 👥 Gestión de Roles
- **Listar roles** con información de usuarios asignados
- **Crear nuevos roles** personalizados
- **Editar roles existentes**
- **Eliminar roles** (con validaciones de seguridad)
- **Ver detalles de roles** y usuarios asignados

### 🛡️ Seguridad y Validaciones
- **Acceso restringido** solo para administradores
- **Validaciones robustas** en formularios
- **Protección contra eliminación** de usuarios con activos asignados
- **Protección del último administrador** del sistema
- **Validación de unicidad** de emails y documentos

## Archivos Creados

### Controladores
- `app/Http/Controllers/UserController.php` - Controlador principal para gestión de usuarios
- `app/Http/Controllers/RolController.php` - Controlador para gestión de roles

### Requests de Validación
- `app/Http/Requests/StoreUserRequest.php` - Validaciones para crear usuarios
- `app/Http/Requests/UpdateUserRequest.php` - Validaciones para actualizar usuarios
- `app/Http/Requests/ResetPasswordRequest.php` - Validaciones para restablecer contraseñas

### Vistas
- `resources/views/usuarios/index.blade.php` - Lista de usuarios con filtros
- `resources/views/usuarios/create.blade.php` - Formulario para crear usuarios
- `resources/views/usuarios/edit.blade.php` - Formulario para editar usuarios
- `resources/views/usuarios/show.blade.php` - Detalles del usuario
- `resources/views/usuarios/reset-password.blade.php` - Formulario para restablecer contraseña
- `resources/views/roles/index.blade.php` - Lista de roles
- `resources/views/roles/create.blade.php` - Formulario para crear roles

### Seeders
- `database/seeders/AdditionalRolesSeeder.php` - Roles adicionales del sistema

## Rutas Implementadas

### Usuarios
- `GET /usuarios` - Lista de usuarios
- `GET /usuarios/create` - Formulario de creación
- `POST /usuarios` - Crear usuario
- `GET /usuarios/{usuario}` - Ver detalles
- `GET /usuarios/{usuario}/edit` - Formulario de edición
- `PUT/PATCH /usuarios/{usuario}` - Actualizar usuario
- `DELETE /usuarios/{usuario}` - Eliminar usuario
- `GET /usuarios/{usuario}/reset-password` - Formulario restablecer contraseña
- `PATCH /usuarios/{usuario}/reset-password` - Restablecer contraseña

### Roles
- `GET /roles` - Lista de roles
- `GET /roles/create` - Formulario de creación
- `POST /roles` - Crear rol
- `GET /roles/{role}` - Ver detalles
- `GET /roles/{role}/edit` - Formulario de edición
- `PUT/PATCH /roles/{role}` - Actualizar rol
- `DELETE /roles/{role}` - Eliminar rol

## Características del Sistema

### 🔍 Búsqueda y Filtros
- Búsqueda por nombre, email o documento
- Filtro por rol
- Paginación automática
- Limpieza de filtros

### 📊 Información Detallada
- Estado del usuario (activo/inactivo)
- Rol asignado con colores distintivos
- Cantidad de activos asignados
- Fechas de registro y última actualización
- Historial de asignaciones

### 🎨 Interfaz de Usuario
- Diseño responsive con Tailwind CSS
- Iconos FontAwesome para mejor UX
- Mensajes de éxito y error
- Confirmaciones para acciones destructivas
- Estados visuales claros

### 🔒 Seguridad
- Solo administradores pueden acceder
- Validación de permisos en cada acción
- Protección contra eliminaciones peligrosas
- Validación de datos en servidor y cliente

## Uso del Módulo

1. **Acceder al módulo**: Desde el menú principal, seleccionar "Usuarios" (solo visible para administradores)
2. **Gestionar usuarios**: Usar las opciones del dropdown para gestionar usuarios o roles
3. **Crear usuarios**: Hacer clic en "Nuevo Usuario" y completar el formulario
4. **Editar usuarios**: Hacer clic en el icono de edición en la lista de usuarios
5. **Restablecer contraseñas**: Usar el icono de llave para cambiar contraseñas
6. **Gestionar roles**: Acceder a "Gestionar Roles" para administrar los roles del sistema

## Consideraciones Importantes

- Los usuarios con activos asignados no pueden ser eliminados
- No se puede eliminar el último administrador del sistema
- El rol "admin" no puede ser eliminado
- Las contraseñas deben tener mínimo 8 caracteres
- Los emails y documentos deben ser únicos en el sistema
- Los usuarios inactivos no pueden iniciar sesión

Este módulo proporciona una solución completa y segura para la gestión de usuarios en el sistema de inventario.
