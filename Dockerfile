# Usa una imagen base con PHP, Composer y extensiones necesarias
FROM php:8.3-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el código del proyecto
COPY . /var/www/html

WORKDIR /var/www/html

# Instala dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Crea el archivo SQLite automáticamente y ejecuta migraciones
RUN mkdir -p /var/www/html/database && \
    touch /var/www/html/database/database.sqlite && \
    php artisan key:generate && \
    php artisan migrate --force

# Da permisos
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Expone el puerto que Render usa
EXPOSE 10000

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000

