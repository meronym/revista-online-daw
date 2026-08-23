FROM php:8.3-fpm

# pdo_mysql pentru baza de date, gd pentru redimensionarea imaginilor la upload,
# opcache pentru viteza; restul extensiilor din imaginea oficiala ne ajung
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j"$(nproc)" gd pdo_mysql opcache \
 && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/revista.ini
COPY fpm.conf /usr/local/etc/php-fpm.d/zz-revista.conf
