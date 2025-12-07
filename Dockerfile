FROM php:8.1-apache

# Install ekstensi yang wajib untuk CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install intl mbstring mysqli pdo pdo_mysql zip

# Aktifkan mod_rewrite (biar URL CI4 cantik tanpa index.php)
RUN a2enmod rewrite

# Install Composer (Manajer paket PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja
WORKDIR /var/www/html

# Beri hak akses penuh ke www-data (agar CI4 bisa nulis log/cache)
RUN chown -R www-data:www-data /var/www/html
