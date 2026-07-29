FROM composer:2 AS vendor

WORKDIR /app

COPY src/composer.json ./

RUN composer config --global audit.block-insecure false \
    && composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --ignore-platform-reqs \
        --prefer-dist

COPY src/ .

RUN composer dump-autoload --optimize --no-dev

FROM node:20-alpine AS assets

WORKDIR /app

COPY src/package.json ./
RUN npm install

COPY src/ .

RUN npm run build

FROM php:8.4-fpm-alpine AS app

RUN apk add --no-cache \
        bash \
        curl \
        freetype \
        icu-libs \
        libjpeg-turbo \
        libpng \
        libzip \
        oniguruma \
        shadow \
        zip \
    && apk add --no-cache --virtual .build-deps \
        freetype-dev \
        icu-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libzip-dev \
        oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    && apk del .build-deps

WORKDIR /var/www/html

COPY src/ .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN addgroup -g 1000 www && adduser -G www -u 1000 -D www \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www:www /var/www/html \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]