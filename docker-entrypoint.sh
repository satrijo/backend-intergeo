#!/bin/sh
# Hentikan script jika ada perintah yang gagal
set -e

# Jalankan migrasi database
# Opsi --force diperlukan untuk berjalan di lingkungan non-interaktif (produksi)
echo "Running database migrations..."
php artisan migrate --force

echo "Entrypoint script finished. Starting PHP-FPM..."

# Jalankan perintah asli yang diberikan ke container (yaitu, "php-fpm")
exec "$@"
