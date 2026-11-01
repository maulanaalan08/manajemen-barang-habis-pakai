# ==========================================
# STAGE 1: Frontend Build (Vite & Tailwind)
# ==========================================
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy dependency definitions
COPY package*.json ./

# Install npm dependencies
RUN npm install

# Copy frontend source files
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

# Compile production assets
RUN npm run build


# ==========================================
# STAGE 2: PHP-FPM Application Server
# ==========================================
FROM php:8.2-fpm

# Set environment
ENV DEBIAN_FRONTEND=noninteractive
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer from official image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy custom PHP configuration
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Copy application source code
COPY . /var/www/html

# Copy compiled frontend assets from Stage 1
COPY --from=frontend /app/public/build /var/www/html/public/build

# Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configure permissions for storage & cache
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy and setup entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
