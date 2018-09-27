FROM base_alpine:latest

RUN apk --no-cache --update add bash
EXPOSE 80

# NGINX
RUN apk --no-cache add nginx \
        && chown -R nginx:nginx /var/www
COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY www.conf /etc/php7/php-fpm.d/www.conf
RUN mkdir -p /run/nginx

ENV PHPIZE_DEPS \
		autoconf file g++ gcc libc-dev make pkgconf re2c php7-dev php7-pear \
        yaml-dev pcre-dev zlib-dev libmemcached-dev cyrus-sasl-dev

RUN apk add --update $PHPIZE_DEPS

# PHP
RUN apk add --update php7 \
        php7-common \
        php7-xml \
        php7-fpm \
        php7-opcache \
        php7-json \
        php7-mbstring \
        php7-session \
        php7-openssl \
        php7-dom \
        php7-gmp \
        php7-pdo \
        php7-pdo_mysql \
        php7-bcmath \
        php7-curl \
        php7-tokenizer \
        nmap \
        mysql mysql-client \
        supervisor \
        vim
