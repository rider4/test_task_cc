FROM php:8.2-fpm-alpine3.20

RUN apk update && apk upgrade \
    && apk add --update --no-cache \
       bash \
       linux-headers \
       composer \
       git \
       shadow\
       libssh-dev \
       rabbitmq-c-dev \
       supervisor \
       autoconf \
       g++ \
       gcc \
       gnupg \
       libgcc \
       make \
       libxml2-dev \
    && apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
    && pecl install xdebug-3.2.2 \
    && docker-php-ext-enable xdebug \
    && apk del pcre-dev ${PHPIZE_DEPS} \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) ctype \
    && docker-php-ext-install -j$(nproc) simplexml \
    && docker-php-ext-install -j$(nproc) xmlwriter \
    && rm -rf /var/cache/apk/*


### install symfony cli
RUN apk add --update --no-cache bash \
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash \
    && apk add symfony-cli

COPY ./config/xdebug.ini $PHP_INI_DIR/conf.d/
#
WORKDIR /var/www
#
ARG UID=9999
USER root
RUN adduser -D -S -G www-data -u ${UID} user
USER user

WORKDIR /var/www

RUN echo "alias ll='ls -alH --color'" >> ~/.bash_aliases

#RUN source ~/.bash_aliases
