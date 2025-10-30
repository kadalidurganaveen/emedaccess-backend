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

### Multi-stage Dockerfile: build frontend with Node, then build PHP image

# Stage 1: build frontend assets with Node
FROM node:18 AS frontend
WORKDIR /app

# Copy package files and install frontend deps (use npm ci if lockfile exists)
COPY package.json package-lock.json* ./
RUN npm ci --silent || npm install --silent

# Copy frontend sources (resources and vite config) and build
COPY resources resources
COPY vite.config.js .
COPY public public
RUN npm run build --if-present

# Stage 2: PHP + Apache
FROM php:8.2-apache

# Install system packages required for PHP extensions and basic utilities
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd || true

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts

# Copy application code
COPY . /var/www/

# Copy built frontend assets from the frontend stage if present
COPY --from=frontend /app/public/build /var/www/public/build

# Copy Apache configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite

# Create storage directories and set permissions
RUN mkdir -p /var/www/storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true \
    && chmod -R 755 /var/www/storage || true

# Copy entrypoint and make executable
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Ensure composer post-install scripts run (package discovery)
RUN composer run-script post-autoload-dump --no-dev --no-interaction || true

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
