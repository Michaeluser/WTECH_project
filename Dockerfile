# 1. Start with a PHP + Apache base image
FROM php:8.2-apache

# 2. Install system dependencies and PHP extensions for Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Install Composer [cite: 1]
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Enable Apache rewrite [cite: 1]
RUN a2enmod rewrite

# 5. Set working directory [cite: 1]
WORKDIR /var/www/html

# 6. Copy project [cite: 1]
COPY . .

# 7. Install PHP dependencies 
RUN composer install --no-dev --optimize-autoloader

# 8. Set Apache to public folder 
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# 9. Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80