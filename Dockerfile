FROM node:22-alpine AS frontend

WORKDIR /var/www/html

RUN corepack enable

COPY package.json package-lock.json ./
RUN npm ci

COPY resources/ resources/
COPY vite.config.ts tsconfig.json components.json ./
COPY public/ public/

RUN npm run build


FROM php:8.4-fpm-alpine AS app

RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    oniguruma-dev \
    sqlite-libs

RUN set -e; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        sqlite-dev \
        oniguruma-dev \
    && docker-php-ext-install -j$(nproc) \
        pdo_sqlite \
        sqlite3 \
        pcntl \
        mbstring \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

COPY --from=frontend /var/www/html/public/build /var/www/html/public/build
COPY . .
COPY docker/entrypoint.sh /entrypoint.sh

RUN mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache/data \
    storage/app/private storage/app/public bootstrap/cache database/demos \
    && ln -sf ../storage/app/public public/storage \
    && chmod +x /entrypoint.sh \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]