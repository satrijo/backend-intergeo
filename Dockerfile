# Tahap 1: Build dependensi PHP dengan Composer
# Menggunakan image composer resmi untuk mengambil dependensi
FROM composer:2.7 AS composer_deps
WORKDIR /app
COPY database/ composer.json composer.lock ./
# Install dependensi tanpa script, dev packages, atau autoloader. Hanya download file.
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --no-autoloader --prefer-dist

# Tahap 2: Build aset frontend dengan Node.js & Vite
# Menggunakan image node alpine yang ringan
FROM node:20-alpine AS node_assets
WORKDIR /app
# Salin file package manager dan file konfigurasi build
COPY package.json package-lock.json* vite.config.ts tsconfig.json ./
# Install dependensi Node.js
RUN npm install
# Salin sisa kode frontend dan jalankan build
COPY resources/ ./resources/
RUN npm run build

# Tahap 3: Build image PHP final untuk produksi
FROM php:8.2-fpm-alpine AS production_image
WORKDIR /var/www

# Install ekstensi PHP yang dibutuhkan oleh Laravel & dependensi sistem
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql bcmath exif opcache zip \
    && rm -rf /var/cache/apk/*

COPY --from=composer_deps /usr/bin/composer /usr/bin/composer


# Salin semua file aplikasi dari direktori lokal ke container
COPY . .

# Salin dependensi PHP yang sudah di-build dari tahap 'composer_deps'
COPY --from=composer_deps /app/vendor/ /var/www/vendor/

# Salin aset frontend yang sudah di-compile dari tahap 'node_assets'
# Ini akan menempatkan folder 'build' di dalam direktori 'public'
COPY --from=node_assets /app/public/build/ /var/www/public/build/

# Buat file .env dari .env.example jika tidak ada (opsional, best practice adalah punya .env sendiri)
RUN if [ ! -f .env ]; then cp .env.example .env; fi


# Jalankan perintah optimasi Laravel
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache
# Atur kepemilikan file agar web server (www-data) bisa menulis ke storage & cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Salin script entrypoint untuk tugas saat container start
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port untuk PHP-FPM
EXPOSE 9000

# Tetapkan Entrypoint dan CMD
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
