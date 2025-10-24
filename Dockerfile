# Imagen base de PHP con Composer y extensiones necesarias
FROM php:8.3-cli

# Instala dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el contenido del proyecto al contenedor
COPY . .

# Instala las dependencias de Laravel (sin dev)
RUN composer install --no-dev --optimize-autoloader

# Crea el archivo SQLite y genera la clave de la app
# NOTA: Render no incluye .env, pero Laravel usa variables del entorno automáticamente.
RUN mkdir -p /var/www/html/database && \
    touch /var/www/html/database/database.sqlite && \
    php artisan key:generate --ansi || true

# Expone el puerto 10000 (Render lo usa automáticamente)
EXPOSE 10000

# Comando para iniciar Laravel en Render
CMD php artisan serve --host=0.0.0.0 --port=10000



