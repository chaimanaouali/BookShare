# syntax=docker/dockerfile:1

ARG PHP_VERSION=php:8.3-fpm-alpine

FROM php:${PHP_VERSION}-fpm-alpine AS base

RUN apk add --no-cache \
    git \
    bash \
    shadow \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    postgresql-libs \
    libxml2-dev \
    curl \
    curl-dev \
    nano \
    tzdata \
    nodejs \
    npm \
    netcat-openbsd && \
    docker-php-ext-configure intl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    intl \
    gd \
    zip \
    opcache && \
    rm -rf /var/cache/apk/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Allow running composer as root inside container
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="/var/www/html/vendor/bin:${PATH}"

# Install dependencies without running post-install scripts (artisan not available yet)
RUN composer install --no-interaction --prefer-dist --no-progress --no-scripts

# Copy rest of the application
COPY . .

# Run composer post-install scripts now that artisan is available
RUN composer run-script post-autoload-dump

# Ensure storage and bootstrap cache are writable
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R ug+rwx storage bootstrap/cache

# Fix ownership of vendor directory (ignore errors for files we can't change)
RUN chown -R www-data:www-data vendor 2>/dev/null || true

# Configure PHP
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
