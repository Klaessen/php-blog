# Use the official PHP image with Apache
FROM php:8.0-apache

# Install system dependencies required for building PHP extensions
RUN apt-get update && apt-get install -y \
    autoconf \
    gcc \
    make \
    pkg-config \
    libssl-dev && apt-get clean \ # Cleaning up the apt cache to reduce image size && rm -rf /var/lib/apt/lists/*

# Install PDO and MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Redis extension via PECL and enable it
RUN pecl install redis \
    && docker-php-ext-enable redis

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the application files to the Apache web root
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Update Apache configuration to serve the public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

# Create the cache directory
RUN mkdir -p /var/www/html/cache

# Set permissions for the web and cache directories
# This ensures www-data has read, write, and execute permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html/cache

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]