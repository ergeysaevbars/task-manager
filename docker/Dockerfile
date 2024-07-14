# Образ системы где будет запускаться код
FROM php:8.3-fpm-alpine

RUN apk add --update --no-cache curl && curl -sS https://getcomposer.org/installer --output installer && php ./installer --version="2.2.5" && mv composer.phar /usr/local/bin/composer

RUN apk add --update --no-cache libzip-dev \
    curl-dev \
    zip \
    && rm -rf /var/cache/apk/*

RUN apk add --no-cache pcre-dev $PHPIZE_DEPS && pecl install redis && docker-php-ext-enable redis.so
RUN docker-php-ext-install pdo_mysql zip

RUN echo "upload_max_filesize = 20M" >> /usr/local/etc/php/conf.d/local.ini \
    && echo "post_max_size = 20M" >> /usr/local/etc/php/conf.d/local.ini \
    && echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/local.ini
