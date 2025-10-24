# Usa la imagen base oficial de PHP con las extensiones necesarias
FROM php:8.3-cli

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev nodejs npm curl && \
    docker-php-ext-install pdo pdo_sqlite bcmath

# Directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Copia el .env.example y renómbralo
COPY .env.example .env

# Instala dependencias PHP
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install --no-dev --optimize-autoloader

# Instala dependencias Node y construye assets (Vite)
RUN npm install && npm run build && ls -la public/build

# Crea base de datos SQLite
RUN mkdir -p database && touch database/database.sqlite

# Genera APP_KEY y ejecuta migraciones
RUN php artisan key:generate
RUN php artisan migrate --force

# Limpia caché de Laravel (opcional pero recomendado)
RUN php artisan config:clear && php artisan view:clear && php artisan cache:clear

# Expone el puerto que Render usará
EXPOSE 10000

# Comando para ejecutar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]







