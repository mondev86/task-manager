# Task Manager

Aplicación de gestión de tareas desarrollada con Laravel 12, Vue 3, MySQL y mas.

## Desc Manager es una aplicación web para gestionar tareasripción

Task diarias con las siguientes funcionalidades:

- **Gestión de tareas**: Crear, editar, completar y eliminar tareas
- **Estados**: Cada tarea puede estar Pendiente, En proceso o Completada
- **Prioridades**: Alta (rojo), Media (naranja), Baja (gris) para organizar visualmente
- **Fechas**: Programar tareas con fecha y hora de inicio
- **Recordatorios**: Enviar recordatorios por WhatsApp o email
- **Diseño moderno**: Interfaz visual con franjas de color según prioridad/estado

Ideal para uso personal o pequeño equipo.

---

## Características

- ✅ **Autenticación** - Registro e inicio de sesión con Laravel Sanctum
- ✅ **CRUD de tareas** - Crear, leer, actualizar y eliminar tareas
- ✅ **Estados** - Pendiente, En proceso, Completada
- ✅ **Prioridades** - Alta (rojo), Media (naranja), Baja (gris)
- ✅ **Diseño visual** - Franja de color según prioridad/tarea completada
- ✅ **Filtros** - Filtrar por estado, fecha y paginación
- ✅ **Recordatorios WhatsApp** - Enviar recordatorios via WhatsApp Web
- ✅ **Email** - Enviar recordatorios por correo (mailto)
- ✅ **Seguridad** - Rate limiting, sesiones cifradas, debug off

## Requisitos

### Local (XAMPP)
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- XAMPP

### Docker
- Docker + Docker Compose

---

## Instalación Local

```bash
# Clonar el proyecto
git clone <repo-url>
cd task-manager

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

## Configuración .env (Local)

```env
APP_NAME=TaskManager
APP_ENV=local
APP_DEBUG=false
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskmanagerdb
DB_USERNAME=root
DB_PASSWORD=tu_password

SESSION_ENCRYPT=true
SESSION_DRIVER=database
```

---

## Docker (WSL/Linux)

### Archivos配置
- `Dockerfile` - Imagen PHP 8.2-FPM
- `docker-compose.yml` - Servicios app + MySQL
- `Makefile` - Comandos rápidos

### Comandos

```bash
# Construir y ejecutar
make build
make up

# Ver logs en tiempo real
make logs

# Entrar al contenedor
make sh

# Ejecutar migraciones
make migrate

# Reiniciar base de datos
make fresh
```

### Configuración .env (Docker)

```env
APP_NAME=TaskManager
APP_ENV=local
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=taskmanagerdb
DB_USERNAME=root
DB_PASSWORD=root

SESSION_ENCRYPT=true
SESSION_DRIVER=database
```

### Puertos

| Servicio | Puerto |
|----------|--------|
| App | 8000 |
| MySQL | 3306 |

---

## Uso

1. Acceder a `http://127.0.0.1:8000`
2. Registrarse / Iniciar sesión
3. Crear tareas con título, descripción, estado, prioridad, fecha y WhatsApp
4. Editar o eliminar tareas desde los iconos
5. Enviar recordatorios por WhatsApp o email

---

## API Endpoints

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/register` | Registrar usuario |
| POST | `/api/login` | Iniciar sesión |
| POST | `/api/logout` | Cerrar sesión |
| GET | `/api/tasks` | Listar tareas (con filtros) |
| POST | `/api/tasks` | Crear tarea |
| PUT | `/api/tasks/{id}` | Actualizar tarea |
| DELETE | `/api/tasks/{id}` | Eliminar tarea |
| POST | `/api/tasks/{id}/whatsapp` | Enviar recordatorio WhatsApp |
| POST | `/api/tasks/{id}/email` | Enviar recordatorio email |

### Filtros GET `/api/tasks`

- `?status=pending|in_progress|completed` - Filtrar por estado
- `?start_date=2026-01-01` - Filtrar desde fecha
- `?end_date=2026-12-31` - Filtrar hasta fecha
- `?per_page=20` - Tareas por página (máx 100)

---

## Errores Comunes

### Error: "SQLSTATE[42S22]: Column not found"
- **Causa**: La columna `status` o `priority` no existe en la base de datos
- **Solución**: Ejecutar migraciones
  ```bash
  php artisan migrate
  ```

### Error: "Route not found" o 404
- **Causa**: Rutas no regeneradas
- **Solución**: 
  ```bash
  php artisan route:clear
  php artisan cache:clear
  ```

### Error: "Target class [XXX] does not exist"
- **Causa**: Cache de Laravel obsoleto
- **Solución**:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  ```

### Error: "Pusher client not found"
- **Causa**: Echo/Pusher no configurado (solo si usas tiempo real)
- **Solución**: No afecta el funcionamiento, eliminar configuración de websockets si no se usa

### Error: "Connection refused" en Docker
- **Causa**: MySQL no está iniciado o credenciales incorrectas
- **Solución**: Verificar `docker-compose up -d` y credenciales en `.env`

### Error: "Vite manifest not found"
- **Causa**: Assets no compilados
- **Solución**:
  ```bash
  npm run build
  ```

### Error: "Session encrypted"
- **Causa**: Intento de acceder a sesión sin HTTPS en producción
- **Solución**: Configurar correctamente `SESSION_ENCRYPT` y `SANCTUM_STATEFUL_DOMAINS`

---

## Tecnologías

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Vue 3, Tailwind CSS 4, Vite
- **Base de datos**: MySQL 8.0
- **Autenticación**: Laravel Sanctum
- **Email**: Mailtrap (desarrollo)

---

## Licencia

MIT License
