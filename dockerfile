FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www/

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy Apache configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# Copy .env.example to .env
COPY .env.example .env

# Install dependencies and optimize
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Set directory permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Generate key and optimize Laravel (only if .env exists)
RUN if [ -f .env ]; then \
        php artisan key:generate --force; \
        php artisan config:cache; \
        php artisan route:cache; \
        php artisan view:cache; \
    fi

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
