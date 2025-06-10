FROM php:7.4-fpm-alpine

COPY --from=composer:1.10.26 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache \
    bash \
    icu-dev \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    curl-dev \
    openssl-dev \
    libxml2-dev \
    mysql-client \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        curl \
        xml \
        simplexml \
        intl \
        zip \
    && rm -rf /var/cache/apk/*
