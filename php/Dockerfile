# Use an official PHP image as the base image
FROM php:8.2-fpm

# Set the working directory to /var/www
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Laravel project files to the container
COPY . .


# Expose port 9000 (PHP-FPM port)
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]
