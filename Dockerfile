FROM php:8.2-fpm
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www/html
COPY . /var/www/html
COPY /providersData /var/www/html/storage/app/providers
COPY .env.example .env
RUN chown -R www:www /var/www/html
RUN chmod -R 775 storage

USER www

RUN composer install
