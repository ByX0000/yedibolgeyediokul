FROM php:8.2-apache

# Sistem bağımlılıkları
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# PHP eklentileri (projede kullanılan PDO + MySQL)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mysqli \
    gd \
    zip \
    opcache

# Apache mod_rewrite aktif et (.htaccess desteği)
RUN a2enmod rewrite headers

# Çalışma dizini
WORKDIR /var/www/html

# Uygulama dosyalarını kopyala
COPY . /var/www/html/

# uploads ve logs dizinlerini oluştur, izinleri ayarla
RUN mkdir -p /var/www/html/uploads \
    && mkdir -p /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/uploads \
    && chmod -R 775 /var/www/html/logs

# Prod'da olmaması gereken dosyaları sil
RUN rm -f /var/www/html/create_database.php \
    && rm -f /var/www/html/nul \
    && rm -f /var/www/html/*.xlsx \
    && rm -f /var/www/html/schools/*-backup.html

# Apache'yi www-data kullanıcısıyla çalıştır
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

EXPOSE 80

CMD ["apache2-foreground"]
