#!/bin/sh
set -e

echo "==> [Docker Entrypoint] Memeriksa konfigurasi Laravel..."

# 1. Pastikan file .env ada
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.docker ]; then
        echo "==> [Docker Entrypoint] Menyalin .env.docker ke .env..."
        cp /var/www/html/.env.docker /var/www/html/.env
    elif [ -f /var/www/html/.env.docker.example ]; then
        echo "==> [Docker Entrypoint] Menyalin .env.docker.example ke .env..."
        cp /var/www/html/.env.docker.example /var/www/html/.env
    elif [ -f /var/www/html/.env.example ]; then
        echo "==> [Docker Entrypoint] Menyalin .env.example ke .env..."
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

# 1b. Sinkronkan aset public ke direktori /var/www/html/public (volume bersama Nginx)
if [ -d /var/www/public_template ]; then
    echo "==> [Docker Entrypoint] Memperbarui aset public ke volume bersama..."
    cp -ru /var/www/public_template/. /var/www/html/public/ 2>/dev/null || true
fi

# 2. Pastikan direktori storage & cache tersedia
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Atur kepemilikan dan permission direktori storage
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    if ! grep -q "^APP_KEY=base64:" /var/www/html/.env 2>/dev/null; then
        echo "==> [Docker Entrypoint] Menghasilkan APP_KEY baru..."
        php artisan key:generate --force
    fi
fi

# 4. Storage link
php artisan storage:link || true

# 5. Tunggu Database Ready jika WAIT_FOR_DB=true
if [ "$WAIT_FOR_DB" = "true" ]; then
    echo "==> [Docker Entrypoint] Menunggu database terhubung ($DB_HOST:$DB_PORT)..."
    
    max_tries=30
    count=0
    until php -r "
        try {
            new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; do
        count=$((count + 1))
        if [ $count -gt $max_tries ]; then
            echo "==> [Docker Entrypoint] Gagal terhubung ke database setelah $max_tries percobaan. Melanjutkan..."
            break
        fi
        echo "==> Menunggu database siap ($count/$max_tries)..."
        sleep 2
    done
    echo "==> [Docker Entrypoint] Database siap!"
fi

# 6. Jalankan Migrasi Otomatis jika RUN_MIGRATIONS=true
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "==> [Docker Entrypoint] Menjalankan migrasi database..."
    php artisan migrate --force || true
    php artisan db:seed --force || true

    # Pastikan Super Admin siap digunakan
    php artisan user:superadmin || true
fi

# 7. Cache optimize untuk production jika APP_ENV=production
if [ "$APP_ENV" = "production" ]; then
    echo "==> [Docker Entrypoint] Mengoptimalkan cache Laravel untuk production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

echo "==> [Docker Entrypoint] Aplikasi siap dijalankan!"

# Jalankan perintah utama kontainer (php-fpm)
exec "$@"

