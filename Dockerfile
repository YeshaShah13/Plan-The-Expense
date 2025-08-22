# Use PHP 8 with Apache
FROM php:8.1-apache

# Copy project files to the container
COPY ./ /var/www/html/

# Enable mod_rewrite if needed
RUN a2enmod rewrite

# Copy Apache config and enable it
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

# Set working directory
WORKDIR /var/www/html

# Install mysqli extension for MySQL
RUN docker-php-ext-install mysqli

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

