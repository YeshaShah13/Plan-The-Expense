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

# Fix permissions - ensure www-data owns the files and set proper permissions
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -name "*.php" -exec chmod 644 {} \; && \
    find /var/www/html -type f -name "*.css" -exec chmod 644 {} \; && \
    find /var/www/html -type f -name "*.js" -exec chmod 644 {} \; && \
    find /var/www/html -type f -name "*.html" -exec chmod 644 {} \; && \
    find /var/www/html -type f -name "*.jpg" -exec chmod 644 {} \; && \
    find /var/www/html -type f -name "*.png" -exec chmod 644 {} \; && \
    find /var/www/html -type f -name "*.jpeg" -exec chmod 644 {} \; && \
    [ -d "/var/www/html/uploads" ] && chmod -R 755 /var/www/html/uploads || true && \
    [ -d "/var/www/html/Assets" ] && chmod -R 755 /var/www/html/Assets || true

# Ensure Apache runs as www-data
RUN sed -i 's/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=www-data\nexport APACHE_RUN_GROUP=www-data/' /etc/apache2/envvars

# Create a startup script to ensure proper permissions at runtime
RUN echo '#!/bin/bash' > /usr/local/bin/start-apache.sh && \
    echo 'echo "Setting up permissions..."' >> /usr/local/bin/start-apache.sh && \
    echo 'chown -R www-data:www-data /var/www/html' >> /usr/local/bin/start-apache.sh && \
    echo 'find /var/www/html -type d -exec chmod 755 {} \;' >> /usr/local/bin/start-apache.sh && \
    echo 'find /var/www/html -type f -exec chmod 644 {} \;' >> /usr/local/bin/start-apache.sh && \
    echo 'echo "Starting Apache..."' >> /usr/local/bin/start-apache.sh && \
    echo 'exec apache2-foreground' >> /usr/local/bin/start-apache.sh && \
    chmod +x /usr/local/bin/start-apache.sh

# Expose port 80
EXPOSE 80

# Use the startup script as the entry point
ENTRYPOINT ["/usr/local/bin/start-apache.sh"]

