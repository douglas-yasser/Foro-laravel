# Usa PHP con Apache
FROM php:8.3-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Copia Composer desde su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia todo el proyecto
COPY . /var/www/html

# Establece directorio de trabajo
WORKDIR /var/www/html

# Crea un archivo .env si no existe
RUN if [ ! -f .env ]; then cp .env.example .env || touch .env; fi

# Instala dependencias sin las de desarrollo
RUN composer install --no-dev --optimize-autoloader

# Da permisos a storage y cache
RUN chmod -R 775 storage bootstrap/cache

# Genera la APP_KEY
RUN php artisan key:generate || true

# Expone el puerto 10000 (Render lo usa)
EXPOSE 10000

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=10000

