FROM php:8.3-apache

# Install system dependencies and required PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy custom Apache virtual host configuration
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf
ENV PORT=80

# Install Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install Composer dependencies (production mode)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Prepare permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy entrypoint script and ensure it is executable
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
