FROM php:8.2-fpm
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www:www /var/www/html
USER www

RUN composer install
