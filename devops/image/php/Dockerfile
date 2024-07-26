#FROM php:8.2.6-fpm-alpine
FROM php:8.2-fpm-alpine3.20

# alias ll='ls -alH --color'

RUN apk update && apk upgrade \
    && apk add --update \
       linux-headers \
       composer \
       git \
       shadow\
       libssh-dev \
       rabbitmq-c-dev \
       supervisor \
       busybox-suid \
    && apk add --update --no-cache --virtual .build-deps \
       autoconf \
       g++ \
       gcc \
       gnupg \
       libgcc \
       make \
    \
    && apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
    && pecl install xdebug-3.2.2 \
    && docker-php-ext-enable xdebug \
    && apk del pcre-dev ${PHPIZE_DEPS} \
    \
    && apk --no-cache add php82-ctype \
    php82-iconv \
    php82-session \
    php82-simplexml \
    php82-tokenizer \
    php82-sodium \
    php82-xmlwriter \
    php82-phar \
    php82-openssl \
    php82-curl \
    php82-dom \
    php82-xml \
    php82-mbstring \
    php82-posix \
    php82-intl \
    php82-pdo \
    php82-opcache \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) ctype \
    && docker-php-ext-install -j$(nproc) tokenizer \
    && docker-php-ext-install -j$(nproc) simplexml \
    && docker-php-ext-install -j$(nproc) xmlwriter \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*


# install symfony cli
RUN apk add --update --no-cache bash \
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash \
    && apk add symfony-cli

COPY ./config/www.pool.conf $PHP_INI_DIR/conf.d/
#COPY ./config/xdebug.ini $PHP_INI_DIR/conf.d/
COPY ./config/php.ini $PHP_INI_DIR/php.ini

WORKDIR /var/www

#ARG UID=9999
#USER root
#
#RUN adduser -D -S -G www-data -u ${UID} user
#
#USER user

WORKDIR /var/www
