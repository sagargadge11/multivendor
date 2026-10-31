# Render Docker deployment for the multivendor repository.
# The repository contains marketplace application files (an overlay), so this
# image creates a fresh Laravel 13 skeleton and then adds the repository code.

FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV PORT=10000

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip zip libpq-dev libzip-dev libicu-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql mbstring bcmath exif pcntl gd intl zip opcache \
    && a2enmod rewrite headers \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*

# Composer is copied from the official Composer image; no Docker Desktop is needed.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Preserve the marketplace source while Laravel's framework skeleton is created.
COPY app/ /tmp/marketplace-source/app/
COPY database/migrations/ /tmp/marketplace-source/database/migrations/
COPY resources/views/ /tmp/marketplace-source/resources/views/

RUN composer create-project laravel/laravel . "^13.0" --no-interaction --prefer-dist \
    && cp -a /tmp/marketplace-source/app/. app/ \
    && mkdir -p database/migrations resources/views \
    && cp -a /tmp/marketplace-source/database/migrations/. database/migrations/ \
    && cp -a /tmp/marketplace-source/resources/views/. resources/views/ \
    && rm -rf /tmp/marketplace-source \
    && composer install --no-dev --optimize-autoloader --no-interaction \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

# Render sets PORT. Apache listens on 10000 by default; set PORT=10000 in Render.
RUN printf '\nListen 10000\n' >> /etc/apache2/ports.conf \
    && sed -ri 's/<VirtualHost \\*:80>/<VirtualHost *:10000>/' /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD ["apache2-foreground"]
