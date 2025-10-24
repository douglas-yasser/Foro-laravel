# Imagen base con PHP 8.3 y Composer
FROM php:8.3-cli

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Crea carpetas necesarias y asigna permisos
RUN mkdir -p /var/www/html/database \
    && touch /var/www/html/database/database.sqlite \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Exponer el puerto 10000 (Render usa este)
EXPOSE 10000

# Ejecutar migraciones si es necesario y arrancar el servidor
CMD php artisan key:generate --ansi \
    && php artisan migrate --force \
    || php artisan migrate:fresh --seed --force \
    && php artisan serve --host=0.0.0.0 --port=10000



