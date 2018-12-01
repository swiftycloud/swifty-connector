FROM php:7.2-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev gnupg git --no-install-recommends

RUN pecl install imagick
RUN pecl install mcrypt-1.0.1
RUN docker-php-ext-enable imagick mcrypt
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash
RUN apt-get install -y nodejs build-essential

RUN npm i -g cross-env

WORKDIR /var/www
COPY . /var/www
