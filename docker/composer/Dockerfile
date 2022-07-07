FROM composer:latest

RUN addgroup -g 1000 www && adduser -G www -g www -s /bin/sh -D www

RUN mkdir -p /var/www/askme

RUN chown www:www /var/www/askme

WORKDIR /var/www/askme
