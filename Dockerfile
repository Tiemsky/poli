FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache nginx wget libpng-dev libxml2-dev libzip-dev zip unzip git icu-dev postgresql-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql gd zip intl bcmath

# Copy project
WORKDIR /var/www/html
COPY . .

# Install Composer & NPM dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader
RUN apk add --no-cache nodejs npm && npm install && npm run build

# Setup Nginx
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "php artisan optimize:clear && php artisan storage:link && php artisan migrate --force && php artisan db:seed --force && (php-fpm -D && nginx -g 'daemon off;')"]

