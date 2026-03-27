FROM php:8.4-apache

# Aggiorna pacchetti e installa dipendenze sistema
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Abilita rewrite e installa estensioni PHP
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo_mysql pdo zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install XDebug 
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Imposta la directory di lavoro
WORKDIR /var/www/html
