FROM php:7.1

RUN apt-get update \
    && apt-get install -y librdkafka-dev git zip unzip wget \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka \
    # composer
    && curl -s https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer