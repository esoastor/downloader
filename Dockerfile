FROM php:8.1.0-fpm

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php.ini
