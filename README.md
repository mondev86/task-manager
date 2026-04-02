<p align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=0:6366f1,100:a855f7&height=220&section=header&text=Task%20Manager&fontSize=62&fontColor=ffffff&fontAlignY=38&desc=Organiza%20tus%20tareas%20con%20estilo%20%F0%9F%9A%80&descAlignY=58&descSize=20&animation=twinkling" />
</p>

<p align="center">
  <a href="https://readme-typing-svg.demolab.com">
    <img src="https://readme-typing-svg.demolab.com?font=Fira+Code&weight=600&size=22&pause=1000&color=6366F1&center=true&vCenter=true&width=600&lines=Laravel+12+%2B+Vue+3+%2B+Tailwind+CSS+4;Gestión+de+tareas+moderna+y+rápida+%E2%9A%A1;WhatsApp+%26+Email+Reminders+%F0%9F%93%AC;Despliega+con+Docker%2C+Herd+o+XAMPP+%F0%9F%90%B3" alt="Typing SVG" />
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/License-MIT-22c55e?style=for-the-badge" />
</p>

---

> 🗂️ Aplicación web de gestión de tareas construida con **Laravel 12**, **Vue 3**, **Tailwind CSS 4** y **MySQL 8**.  
> Incluye autenticación, estados, prioridades, filtros, paginación y recordatorios por **WhatsApp** y **email**.  
> Ideal para uso personal o en equipo pequeño. Funciona con **Laravel Herd**, **XAMPP** o **Docker**.

---

## ✨ Características

| | Funcionalidad | Descripción |
|---|---|---|
| 🔐 | **Autenticación** | Registro e inicio de sesión con Laravel Sanctum (token Bearer) |
| 📋 | **CRUD completo** | Crear, leer, actualizar y eliminar tareas |
| 🔄 | **Estados** | Pendiente, En proceso, Completada |
| 🎨 | **Prioridades** | Alta 🔴, Media 🟠, Baja ⚪ con franja visual de color |
| 📅 | **Fechas** | Programar fecha/hora de inicio y fin por tarea |
| 🔍 | **Filtros y paginación** | Filtrar por estado, rango de fechas y resultados por página |
| 💬 | **Recordatorio WhatsApp** | Abre WhatsApp Web con mensaje prellenado |
| 📧 | **Recordatorio Email** | Envío de correo con plantilla HTML via Mailtrap |
| 🛡️ | **Seguridad** | Rate limiting, sesiones cifradas, `APP_DEBUG=false`, logs de auditoría |

---

## 🛠️ Tecnologías

<p align="center">
  <img src="https://img.shields.io/badge/Backend-Laravel_12_%7C_PHP_8.2-FF2D20?style=flat-square&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Frontend-Vue_3_%7C_Tailwind_4_%7C_Vite-4FC08D?style=flat-square&logo=vue.js&logoColor=white" />
  <img src="https://img.shields.io/badge/Database-MySQL_8.0-4479A1?style=flat-square&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Auth-Laravel_Sanctum-FF2D20?style=flat-square&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Email-Mailtrap-0ea5e9?style=flat-square&logo=maildotru&logoColor=white" />
  <img src="https://img.shields.io/badge/Containers-Docker_Compose-2496ED?style=flat-square&logo=docker&logoColor=white" />
</p>

---

## 📦 Requisitos

| Entorno | Requisitos |
|---------|-----------|
| 🟣 **Herd** | Laravel Herd, Node.js 18+, MySQL 8 |
| 🟠 **XAMPP** | PHP 8.2+, Composer, Node.js 18+, MySQL 8, XAMPP |
| 🐳 **Docker** | Docker Desktop + Docker Compose |

---

## 🚀 Instalación

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

## 📱 Uso de la aplicación

1. Registrarse o iniciar sesión.
2. Crear una tarea con título, descripción, estado, prioridad, fecha de inicio y número de WhatsApp.
3. Editar o eliminar tareas desde los iconos de cada tarjeta.
4. Filtrar por estado y rango de fechas; paginar resultados.
5. Enviar recordatorio por WhatsApp (abre WhatsApp Web con mensaje prellenado).
6. Enviar recordatorio por email directamente desde la tarjeta.

---

## 🔌 API Endpoints

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

## ⚠️ Errores comunes

| Error | Causa | Solución |
|-------|-------|---------|
| `SQLSTATE[42S22]: Column not found` | Faltan columnas `status` o `priority` | `php artisan migrate` |
| `404` o ruta no encontrada | Cache de rutas obsoleto | `php artisan route:clear` |
| `Target class does not exist` | Cache de Laravel desactualizado | `php artisan config:clear && php artisan cache:clear` |
| `Vite manifest not found` | Assets sin compilar | `npm run build` |
| `Connection refused` (Docker) | MySQL no levantado | `make up` y verificar `.env` |
| `Unauthenticated` en API | Token no enviado o expirado | Incluir `Authorization: Bearer {token}` |

---

## 📄 Licencia

MIT License

---

<p align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=0:a855f7,100:6366f1&height=120&section=footer" />
</p>
