FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos necesarios para composer
COPY composer.json composer.lock ./

# Instalar dependencias
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# Copiar resto de archivos
COPY . .

# Generar key (crear .env si no existe)
RUN cp .env.example .env || true
RUN php artisan key:generate --force

# Compilar assets (primero npm install)
RUN npm install && npm run build

# Configurar permisos
RUN chmod -R 755 storage bootstrap/cache

# Exponer puerto
EXPOSE 8000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
