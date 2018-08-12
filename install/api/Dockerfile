FROM base_nginx-php:latest

ADD start-app.sh /usr/bin/start-app.sh

ENV BUILD_DEPS \
                cmake \
                autoconf \
                g++ \
                gcc \
                make \
                pcre-dev \
                openssl-dev \
                libuv-dev \
                gmp-dev
ENV INSTALL_DIR /usr/src/datastax-php-driver

RUN apk update && apk add --no-cache --virtual .build-deps $BUILD_DEPS \
    && apk add --no-cache git libuv gmp libssl1.0 \
    && git clone https://github.com/datastax/php-driver.git $INSTALL_DIR \
    && cd $INSTALL_DIR \
    && git submodule update --init \
    && cd ext && ./install.sh \
    && echo -e "; DataStax PHP Driver\nextension=cassandra.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"` \
    && apk del .build-deps \
    && rm -rf $INSTALL_DIR

CMD [ "/bin/bash", "start-app.sh" ]