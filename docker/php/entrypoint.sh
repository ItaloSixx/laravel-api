#!/bin/sh
set -e

echo "🔧 [entrypoint] Verificando dependências (vendor)..."
if [ ! -f "vendor/autoload.php" ]; then
    echo "  → Pasta vendor não encontrada. Instalando pacotes do Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

echo "🔧 [entrypoint] Verificando APP_KEY..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

echo "🔧 [entrypoint] Aguardando banco de dados..."
until php -r "try { new PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); } catch (Exception) { exit(1); }" > /dev/null 2>&1; do
    echo "  → Banco não pronto, aguardando 3s..."
    sleep 3
done

echo "🔧 [entrypoint] Rodando migrations..."
php artisan migrate --force

echo "🔧 [entrypoint] Limpando caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ [entrypoint] Container pronto!"
exec php-fpm
