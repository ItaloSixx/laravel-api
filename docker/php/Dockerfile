FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    zip \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    mysql-client \
    sqlite \
    sqlite-dev

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    intl

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN addgroup -g 1000 -S www && \
    adduser  -u 1000 -S www -G www

COPY --chown=www:www . .

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www:www storage bootstrap/cache

USER www

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
