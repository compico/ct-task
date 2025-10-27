FROM php:8.4-fpm-alpine AS base

FROM base AS build

RUN apk add --no-cache --virtual .build-deps \
        autoconf \
        g++ \
        make \
        libtool \
        zlib-dev \
        git \
        curl-dev \
        pkgconf \
        openssl-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        libxml2-dev \
        sqlite-dev \
        linux-headers \
        libpq-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        pdo_pgsql \
        gd \
        zip \
        mbstring \
        intl \
        xml \
        soap \
        bcmath \
        pcntl \
        sockets \
        exif \
        opcache

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

FROM base AS app

RUN apk add --no-cache \
        zlib \
        libpng \
        libjpeg-turbo \
        freetype \
        libzip \
        oniguruma \
        icu \
        libxml2 \
        sqlite \
        mysql-client \
        bash \
        make \
        libpq

RUN addgroup -g 82 www-data 2>/dev/null || true \
    && adduser -u 1000 -G www-data -s /bin/sh -D -h /var/www appuser 2>/dev/null || true

COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/laravel.ini \
    && echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "max_input_vars = 3000" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "date.timezone = UTC" >> /usr/local/etc/php/conf.d/laravel.ini

# RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/opcache.ini

RUN mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/bootstrap/cache

WORKDIR /var/www

RUN chown -R appuser:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

USER appuser

EXPOSE 9000

CMD ["php-fpm"]
