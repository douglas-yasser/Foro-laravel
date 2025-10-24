# Usa la imagen base oficial de PHP con las extensiones necesarias
FROM php:8.3-cli

# Instala dependencias del sistema y extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev nodejs npm && \
    docker-php-ext-install pdo pdo_sqlite bcmath

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias de Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install --no-dev --optimize-autoloader

# Compila los assets de Vite
RUN npm install && npm run build

# Crea la base de datos SQLite y ejecuta las migraciones
RUN mkdir -p /var/www/html/database && \
    touch /var/www/html/database/database.sqlite && \
    php artisan key:generate && \
    php artisan migrate --force

# Expone el puerto que Render usará (10000)
EXPOSE 10000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]





