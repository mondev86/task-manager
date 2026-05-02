# Task Manager

Aplicación web de gestión de tareas desarrollada con **Laravel 12**, **Vue 3**, **Tailwind CSS 4** y **MySQL 8**. Incluye autenticación, estados, prioridades, filtros, paginación y recordatorios por WhatsApp y email.

Ideal para uso personal o en equipo pequeño. Funciona con **Laravel Herd**, **XAMPP** o **Docker**.

---

## Características

- ✅ **Autenticación** — Registro e inicio de sesión con Laravel Sanctum (token Bearer, [ver detalles](#autenticación))
- ✅ **CRUD completo** — Crear, leer, actualizar y eliminar tareas
- ✅ **Estados** — Pendiente, En proceso, Completada
- ✅ **Prioridades** — Alta (rojo), Media (naranja), Baja (gris) con franja visual de color
- ✅ **Fechas** — Programar fecha/hora de inicio y fin por tarea
- ✅ **Filtros y paginación** — Filtrar por estado, rango de fechas y número de resultados por página
- ✅ **Recordatorio WhatsApp** — Abre WhatsApp Web con mensaje prellenado
- ✅ **Recordatorio Email** — Envío de correo con plantilla HTML via Mailtrap
- ✅ **Seguridad** — Rate limiting, sesiones cifradas, `APP_DEBUG=false` por defecto, logs de auditoría en envíos

---

## Tecnologías

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Vue 3, Tailwind CSS 4, Vite |
| Base de datos | MySQL 8.0 |
| Autenticación | Laravel Sanctum |
| Email | Mailtrap (desarrollo) |
| Contenedores | Docker + Docker Compose |
---

## Autenticación

La API utiliza **Laravel Sanctum** con dos modos disponibles:
- **SPA Mode**: Basado en cookies, protección CSRF, diseñado para SPAs de primera parte (mismo dominio).
- **Token Authentication**: Basado en Bearer tokens, diseñado para terceros o aplicaciones móviles.

### Elección del proyecto
Se eligió **Token Authentication (Bearer)** para este proyecto porque el frontend Vue 3 consume la API de forma independiente, permitiendo flexibilidad para futuras integraciones. La autenticación requiere el header `Authorization: Bearer {token}` en todos los endpoints protegidos.

---


## Requisitos

| Entorno | Requisitos |
|---------|-----------|
| **Herd** | Laravel Herd, Node.js 18+, MySQL 8 |
| **XAMPP** | PHP 8.2+, Composer, Node.js 18+, MySQL 8, XAMPP |
| **Docker** | Docker Desktop + Docker Compose |

---

## Instalación

### Pasos comunes (Herd, XAMPP o servidor propio)

```bash
# 1. Clonar el proyecto
git clone https://github.com/mondev86/task-manager.git
cd task-manager

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node
npm install

# 4. Copiar archivo de entorno
cp .env.example .env     # Linux/Mac
copy .env.example .env   # Windows

# 5. Generar clave de aplicación
php artisan key:generate

# 6. Ejecutar migraciones
php artisan migrate

# 7. Compilar assets
npm run build
```

---

### Opción A — Laravel Herd (recomendado en Windows/Mac)

1. Instala [Laravel Herd](https://herd.laravel.com) y asegúrate de tener PHP 8.2 y MySQL activos.
2. Coloca el proyecto dentro de la carpeta de sitios de Herd (por defecto `~/Herd`).
3. Crea la base de datos `taskmanagerdb` en el MySQL de Herd.
4. Configura `.env`:

```env
APP_NAME=TaskManager
APP_ENV=local
APP_DEBUG=false
APP_URL=http://task-manager.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskmanagerdb
DB_USERNAME=root
DB_PASSWORD=tu_password

SESSION_ENCRYPT=true
SESSION_DRIVER=database
QUEUE_CONNECTION=sync
```

5. Accede a `http://task-manager.test`

---

### Opción B — XAMPP

1. Inicia Apache y MySQL desde el panel de XAMPP.
2. Crea la base de datos `taskmanagerdb` en `http://localhost/phpmyadmin`.
3. Configura `.env`:

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
DB_PASSWORD=

SESSION_ENCRYPT=true
SESSION_DRIVER=database
QUEUE_CONNECTION=sync
```

4. Levanta el servidor:

```bash
php artisan serve
```

5. Accede a `http://127.0.0.1:8000`

---

### Opción C — Docker (WSL/Linux/Windows)

El proyecto incluye `Dockerfile`, `docker-compose.yml` y `Makefile`.

```bash
# Construir y levantar contenedores
make build
make up

# Ejecutar migraciones dentro del contenedor
make migrate

# Ver logs en tiempo real
make logs

# Entrar al contenedor
make sh

# Reiniciar base de datos con seeders
make fresh
```

Configuración `.env` para Docker:

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
QUEUE_CONNECTION=sync
```

| Servicio | Puerto local |
|----------|-------------|
| Aplicación | 8000 |
| MySQL | 3308 |

Accede a `http://localhost:8000`

---

## Uso de la aplicación

1. Registrarse o iniciar sesión.
2. Crear una tarea con título, descripción, estado, prioridad, fecha de inicio y número de WhatsApp.
3. Editar o eliminar tareas desde los iconos de cada tarjeta.
4. Filtrar por estado y rango de fechas; paginar resultados.
5. Enviar recordatorio por WhatsApp (abre WhatsApp Web con mensaje prellenado).
6. Enviar recordatorio por email directamente desde la tarjeta.

---

## API Endpoints

Todos los endpoints (excepto login/register) requieren header `Authorization: Bearer {token}`.

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/register` | Registrar usuario |
| POST | `/api/login` | Iniciar sesión |
| POST | `/api/logout` | Cerrar sesión |
| GET | `/api/tasks` | Listar tareas (con filtros y paginación) |
| POST | `/api/tasks` | Crear tarea |
| PUT | `/api/tasks/{id}` | Actualizar tarea |
| DELETE | `/api/tasks/{id}` | Eliminar tarea |
| POST | `/api/tasks/{id}/whatsapp` | Generar URL de recordatorio WhatsApp |
| POST | `/api/tasks/{id}/email` | Enviar recordatorio por email |

### Parámetros de filtrado — `GET /api/tasks`

| Parámetro | Valores | Descripción |
|-----------|---------|-------------|
| `status` | `pending`, `in_progress`, `completed` | Filtrar por estado |
| `start_date` | `YYYY-MM-DD` | Tareas desde esta fecha |
| `end_date` | `YYYY-MM-DD` | Tareas hasta esta fecha |
| `per_page` | número (máx 100) | Resultados por página |

---

## Errores comunes

| Error | Causa | Solución |
|-------|-------|---------|
| `SQLSTATE[42S22]: Column not found` | Faltan columnas `status` o `priority` | `php artisan migrate` |
| `404` o ruta no encontrada | Cache de rutas obsoleto | `php artisan route:clear` |
| `Target class does not exist` | Cache de Laravel desactualizado | `php artisan config:clear && php artisan cache:clear` |
| `Vite manifest not found` | Assets sin compilar | `npm run build` |
| `Connection refused` (Docker) | MySQL no levantado | `make up` y verificar `.env` |
| `Unauthenticated` en API | Token no enviado o expirado | Incluir `Authorization: Bearer {token}` |

---

## Versiones

- **Task Manager** (este repositorio): Con funcionalidades core. Se mantendrá como versión de uso para usuario individual.
- **Task Manager Advanced**: Es un proyecto con funcionalidades avanzadas para equipos de usuarios. [Repositorio](https://github.com/mondev86/task-manager-advanced).

---

## Pruebas

El proyecto incluye pruebas funcionales (Feature Tests) básicas con PHPUnit:
1. Test de login: Valida credenciales válidas (éxito) e inválidas (error 401).
2. Test de creación de tareas: Valida que usuarios autenticados puedan crear tareas, no autenticados no.

Para ejecutar las pruebas:
```bash
php artisan test
```

---

## Licencia

MIT License
