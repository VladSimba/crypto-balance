FROM php:8.3-fpm

# Системные пакеты
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем расширения PHP
RUN docker-php-ext-install zip pdo_mysql pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /var/www

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Копируем проект
COPY ./www /var/www

# Порт и запуск PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]