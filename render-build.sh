#!/usr/bin/env bash
set -e

echo "🔧 Instalando dependencias..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "📋 Copiando archivo .env..."
cp .env.example .env

echo "🔑 Generando APP_KEY..."
php artisan key:generate --force

echo "🗄️ Creando base de datos SQLite..."
mkdir -p database
touch database/database.sqlite
chmod 664 database/database.sqlite

echo "📁 Creando directorios necesarios..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p bootstrap/cache

echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache

echo "🚀 Ejecutando migraciones..."
php artisan migrate --force --no-interaction

echo "⚡ Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completado!"
```

## 3. Actualiza `.gitattributes`

Crea o actualiza este archivo:
```
* text=auto eol=lf
*.sh text eol=lf