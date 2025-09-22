# Dockerfile for NCA Toolkit PHP Client

# Use the official PHP image with Apache
FROM php:8.1-apache

# Install curl and other required packages
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    python3 \
    python3-pip \
    ffmpeg \
    libzip-dev \
    unzip \
    && docker-php-ext-install curl gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install yt-dlp
RUN pip3 install --break-system-packages yt-dlp

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy all files to the container
COPY . .

# Set permissions for the web directory
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create cache directory and set permissions
RUN mkdir -p /var/www/.cache && chown -R www-data:www-data /var/www/.cache

# Increase PHP upload limits
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]