FROM dunglas/frankenphp:1-php8.4

RUN apt update && apt install -y --no-install-recommends \
    ca-certificates \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    pdo_mysql \
    gd \
    intl \
    zip \
    bcmath \
    opcache \
    curl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . /app

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 80

CMD ["frankenphp", "php-server", "--root", "public/"]