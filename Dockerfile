FROM php:8.3-apache

# 1. Install ekstensi yang wajib untuk CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install intl mbstring mysqli pdo pdo_mysql zip

# 2. Aktifkan mod_rewrite (Wajib buat CI4)
RUN a2enmod rewrite

# 3. SETTING PENTING: Arahkan Apache ke folder 'public'
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Setup permission folder agar bisa ditulis (writable)
WORKDIR /var/www/html
# Kita jalankan chown nanti lewat entrypoint atau command manual jika perlu,
# tapi sementara kita set permission lebar agar aman di development
RUN chmod -R 777 /var/www/html