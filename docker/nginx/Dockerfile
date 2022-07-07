FROM nginx:alpine

RUN apk update && apk add bash

ADD ./nginx.conf /etc/nginx/nginx.conf
ADD ./host.conf /etc/nginx/conf.d/default.conf

RUN mkdir -p /var/www/askme

RUN addgroup -g 1000 www && adduser -G www -g www -s /bin/sh -D www

RUN chown www:www /var/www/askme
