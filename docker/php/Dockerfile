FROM php:7-fpm
RUN apt-get update \
    && apt-get install -y git zlib1g-dev \
    && docker-php-ext-install zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/