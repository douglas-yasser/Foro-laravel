# Usa la imagen base oficial de PHP con las extensiones necesarias
FROM php:8.3-cli

# Instala dependencias del sistema y extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev nodejs npm curl && \
    docker-php-ext-install pdo pdo_sqlite bcmath

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Copia el .env.example y renómbralo a .env
COPY .env.example .env

# Instala dependencias de Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install --no-dev --optimize-autoloader

# Instala dependencias de Node y construye assets con Vite
RUN npm install && npm run build

# Crea la base de datos SQLite
RUN mkdir -p database && touch database/database.sqlite

# Genera key de Laravel y ejecuta migraciones
RUN php artisan key:generate
RUN php artisan migrate --force

# Expone el puerto que Render usará
EXPOSE 10000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]






