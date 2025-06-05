FROM php:7.4-fpm-alpine

COPY --from=composer:1.10.26 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache \
    bash \
    icu-dev \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    mysql-client \
    libxml2-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
    && rm -rf /var/cache/apk/*
