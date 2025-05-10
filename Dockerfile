FROM php:8.1-fpm

# Устанавливаем системные библиотеки для JPEG/PNG/FreeType и pdo_mysql
RUN apt-get update \
 && apt-get install -y \
      libpng-dev \
      libjpeg-dev \
      libfreetype6-dev \
      zip unzip git \
 && docker-php-ext-configure gd \
      --with-jpeg=/usr/include/ \
      --with-freetype=/usr/include/ \
 && docker-php-ext-install \
      pdo pdo_mysql gd \
 && rm -rf /var/lib/apt/lists/*

# Копируем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY /uploads.ini /usr/local/etc/php/conf.d/uploads.ini
