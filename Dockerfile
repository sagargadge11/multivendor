
FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV PORT=10000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring bcmath exif pcntl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN docker-php-ext-install intl zip opcache

# Enable Apache modules
RUN a2enmod rewrite headers

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Preserve existing marketplace application files
COPY app/ /tmp/marketplace-source/app/
COPY database/migrations/ /tmp/marketplace-source/database/migrations/
COPY resources/views/ /tmp/marketplace-source/resources/views/

# Create Laravel 13 and merge marketplace files
RUN composer create-project laravel/laravel . "^13.0" --no-interaction --prefer-dist \
    && cp -a /tmp/marketplace-source/app/. app/ \
    && mkdir -p database/migrations resources/views \
    && cp -a /tmp/marketplace-source/database/migrations/. database/migrations/ \
    && cp -a /tmp/marketplace-source/resources/views/. resources/views/ \
    && rm -rf /tmp/marketplace-source \
    && composer install --no-dev --optimize-autoloader --no-interaction \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

# Configure Apache to serve Laravel's public directory
RUN sed -ri 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's/^Listen 80$/Listen 10000/' /etc/apache2/ports.conf \
    && printf '\n<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' >> /etc/apache2/apache2.conf

EXPOSE 10000

CMD ["apache2-foreground"]
