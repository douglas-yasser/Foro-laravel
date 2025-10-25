# Usa la imagen base oficial de PHP con extensiones necesarias
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

# Configura APP_URL para producción
RUN sed -i "s|APP_URL=.*|APP_URL=https://foro-laravel-3.onrender.com|" .env

# Instala dependencias PHP con Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install --no-dev --optimize-autoloader

# Instala Node.js y genera assets con Vite
RUN npm install && npm run build && ls -la public/build

# Crea base de datos SQLite si no existe
RUN mkdir -p database && touch database/database.sqlite

# Genera APP_KEY y ejecuta migraciones
RUN php artisan key:generate
RUN php artisan migrate --force

# 🟩 Inserta categorías por defecto (solo si no existen)
RUN php artisan tinker --execute='if (App\Models\Category::count() == 0) { App\Models\Category::insert([ \
    ["name" => "General"], \
    ["name" => "Programación"], \
    ["name" => "Laravel"], \
    ["name" => "Base de datos"] \
]); }'

# Limpia caché de Laravel
RUN php artisan config:clear && php artisan view:clear && php artisan cache:clear

# Expone el puerto que Render usará
EXPOSE 10000

# Forzar HTTPS en producción mediante AppServiceProvider
RUN echo "<?php\n\nnamespace App\Providers;\n\nuse Illuminate\Support\ServiceProvider;\nuse Illuminate\Support\Facades\URL;\n\nclass AppServiceProvider extends ServiceProvider\n{\n    public function register(): void {}\n\n    public function boot(): void\n    {\n        if (config('app.env') === 'production') {\n            URL::forceScheme('https');\n        }\n    }\n}" > app/Providers/AppServiceProvider.php

# Comando de inicio de Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]










