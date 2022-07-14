FROM php:8-fpm

ADD ./php.conf /usr/local/etc/php-fpm.d/www.conf
ADD ./php.ini /usr/local/etc/php/php.ini

ENV TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN dpkg-reconfigure -f noninteractive tzdata

RUN apt-get update && \
    apt-get -y install \
        gnupg2 && \
    apt-key update && \
    apt-get update && \
    apt-get -y install \
            g++ \
            git-core  \
            curl \
            libcurl3-dev \
            nano \
            unzip \
            libpq-dev \
            libmemcached-dev \
            memcached \
            libcurl4-openssl-dev \
            libssl-dev \
            libxml2-dev \
            libzip-dev \
            wget \
	    nodejs \
            supervisor \
        --no-install-recommends && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# PHP's extensions.
RUN docker-php-ext-configure bcmath && \
        docker-php-ext-install \
            curl \
            xml \
            mysqli \
            pdo_mysql \
            zip \
            && docker-php-ext-enable mysqli

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd

RUN pecl channel-update pecl.php.net

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#COPY ./laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY ./supervisor.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN mkdir -p /var/www/askme

RUN chown www:www /var/www/askme

WORKDIR /var/www/askme

