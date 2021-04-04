############
# php-base #
############
ARG BASE_IMAGE=php:8.0.3-cli-alpine3.13
FROM ${BASE_IMAGE} as php-base

WORKDIR /app


###########
# php-dev #
###########
FROM php-base as php-dev

# install xdebug
RUN \
    apk add --no-cache $PHPIZE_DEPS \
    && \
    pecl install xdebug-3.0.3 \
    && \
    docker-php-ext-enable xdebug \
    && \
    apk del $PHPIZE_DEPS

ENV APP_USER=app
ENV APP_GROUP=app

RUN \
    addgroup -S "$APP_GROUP" \
    && \
    adduser \
        -S "$APP_USER" \
        -G "$APP_GROUP" \
    && \
    apk --no-cache add shadow su-exec

COPY resources/docker/php/php.ini /usr/local/etc/php/
COPY resources/docker/php/bin/* /usr/local/bin/
RUN chmod +rx /usr/local/bin/*

VOLUME /app

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/docker-php-entrypoint"]
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public/", "public/index.php"]
