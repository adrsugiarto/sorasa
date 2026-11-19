#!/bin/sh
set -e

# 1. Pastikan folder storage & permission siap untuk FILESYSTEM_DISK=public
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 2. Buat symlink public/storage
php artisan storage:link --force

# 3. Jalankan migrasi DULUAN agar tabel cache, sessions, dan jobs terbentuk
php artisan migrate --force

# 4. Bersihkan dan bangun ulang cache konfigurasi produksi
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Jalankan Apache
exec apache2-foreground