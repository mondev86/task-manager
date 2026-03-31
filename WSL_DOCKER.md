# Docker en WSL - Windows

## Requisitos Previos

1. **WSL2 instalado** (recomendado)
2. **Docker Desktop para Windows** con integración WSL

## Instalación

### 1. Instalar WSL
```
powershell
wsl --install
```

### 2. Configurar Docker Desktop
- Abrir Docker Desktop
- Ir a Settings > Resources > WSL Integration
- Habilitar la distribución de WSL que uses (Ubuntu, Debian, etc.)

## Comandos para ejecutar el proyecto

### Construir los contenedores
```
bash
docker-compose build
```

### Iniciar los contenedores
```
bash
docker-compose up -d
```

### Ver logs
```
bash
docker-compose logs -f
```

### Detener los contenedores
```
bash
docker-compose down
```

### Entrar al contenedor de la aplicación
```
bash
docker-compose exec app bash
```

### Ejecutar migraciones
```
bash
docker-compose exec app php artisan migrate
```

### Usar Makefile (más fácil)
```
bash
make build    # Construir contenedores
make up       # Iniciar contenedores
make down     # Detener contenedores
make logs     # Ver logs
make sh       # Entrar al contenedor
make migrate  # Ejecutar migraciones
```

## Acceder a la aplicación

- **Aplicación:** http://localhost:8000
- **Base de datos MySQL:** localhost:3308

## Notas para WSL

### Si tienes problemas de permisos
```
bash
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
```

### Para usar mysql desde WSL
```
bash
docker-compose exec mysql bash
```

### Variables de entorno importantes
El archivo `.env` ya está configurado con:
- DB_HOST=mysql (nombre del servicio en Docker Compose)
- DB_PORT=3306 (puerto interno del contenedor MySQL)
- Puerto expuesto: 3308 (para acceder desde Windows)

## Solución de problemas

### Docker no inicia
```
bash
# En Windows PowerShell como administrador
Restart-Service com.docker.service
```

### Error de permisos
```
bash
# En WSL
sudo usermod -aG docker $USER
```

### Reiniciar contenedores
```
bash
docker-compose down
docker-compose up -d --build
