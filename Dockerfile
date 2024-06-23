# Use the official PHP image with Apache
FROM php:8.0-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the application files to the Apache web root
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html

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