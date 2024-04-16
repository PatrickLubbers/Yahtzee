# Use the official PHP with Apache image
FROM php:apache

# Copy your PHP files to the web server root directory
COPY ./ /var/www/html/

# Optionally, you can add additional PHP extensions if needed
# For example, to install the MySQL extension, you can use:
# RUN docker-php-ext-install mysqli pdo pdo_mysql

# The PHP image already has Apache configured to work with PHP
# You don't need to manually configure Apache for PHP

# Expose port 80
EXPOSE 80