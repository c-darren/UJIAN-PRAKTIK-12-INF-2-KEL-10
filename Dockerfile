FROM php:8.3-fpm

WORKDIR /var/www

# Add node user
RUN useradd -m node && \
    mkdir -p /var/www/node_modules && \
    chown -R node:node /var/www

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    supervisor

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Supervisor setup
RUN mkdir -p /var/log/supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Tambahkan permission yang tepat
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www

EXPOSE 8000 5173 5050

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]