FROM php:8.4-fpm-alpine AS app

RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    sqlite-libs \
    oniguruma \
    nodejs \
    npm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN composer dump-autoload --optimize \
    && npm run build \
    && php artisan storage:link 2>/dev/null || ln -sf ../storage/app/public public/storage \
    && mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache/data \
        storage/app/private storage/app/public bootstrap/cache database/demos \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]