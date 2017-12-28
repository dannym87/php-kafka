FROM php:7.1

RUN apt-get update \
    && apt-get install -y librdkafka-dev git zip unzip wget \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka \
    # composer
    && wget https://raw.githubusercontent.com/composer/getcomposer.org/863c57de1807c99d984f7b56f0ea56ebd7e5045b/web/installer -O - -q | php -- --quiet --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer