FROM php:7.4-fpm-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk update && apk add git

RUN docker-php-ext-install pdo pdo_mysql