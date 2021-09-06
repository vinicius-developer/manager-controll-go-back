FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

COPY . .

RUN useradd -G www-data,root -u 13215 -d /home/vinicius vinicius
RUN mkdir -p /home/vinicius/.composer && \
    chown -R vinicius:vinicius /home/vinicius

EXPOSE 80

CMD php -t public/ -S 127.0.0.1:3000

