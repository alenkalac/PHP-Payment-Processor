FROM php:7.4-apache

RUN pecl install apcu

RUN apt-get update && \
   apt-get install -y \
   libzip-dev \
   git

RUN docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable apcu

WORKDIR /app

RUN sed -ri -e 's!/var/www/html!/app/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/app/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/

COPY . .

RUN PATH=$PATH:/app/vendor/bin:bin
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN chmod +x /app/entry-point.sh && sed -i -e 's/\r$//' /app/entry-point.sh

ENTRYPOINT ["/app/entry-point.sh"]
