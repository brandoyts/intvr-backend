FROM php:8-fpm-alpine

ARG uid
ARG user

RUN apk add shadow
RUN docker-php-ext-install pdo pdo_mysql


RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && chown -R $user:$user /home/$user
RUN chown -R $user:$user /var/www

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
USER $user
