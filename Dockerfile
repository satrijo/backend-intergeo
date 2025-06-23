# Tahap 1: Build dependencies
FROM composer:2.7 as vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist

# Tahap 2: Production image
FROM php:8.2-fpm-alpine

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

WORKDIR /var/www

# Copy vendor dari tahap sebelumnya
COPY --from=vendor /app/vendor ./vendor

# Copy source code
COPY . .

# Permissions (opsional, tergantung kebutuhan)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"] 