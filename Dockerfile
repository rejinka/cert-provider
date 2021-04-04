ARG BASE_IMAGE=php:8.0.3-cli-alpine3.13

FROM composer:2.0.8 as composer

############
# php-base #
############
FROM ${BASE_IMAGE} as php-base

WORKDIR /app


#########
# psalm #
#########
FROM php-base as psalm

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN composer global require "vimeo/psalm:4.7.0"


##########
# php-ci #
##########
FROM php-base as php-ci

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY --from=psalm /root/.composer /opt/composer
COPY composer.* ./
RUN composer install --no-scripts
COPY . ./


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
ENV CACHE_DIR=/usr/local/cache

RUN \
    addgroup -S "$APP_GROUP" \
    && \
    adduser \
        -S "$APP_USER" \
        -G "$APP_GROUP" \
    && \
    apk --no-cache add shadow su-exec \
    && \
    mkdir /usr/local/cache \
    && \
    chown $APP_USER:$APP_GROUP . /usr/local/cache

# install composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME ${CACHE_DIR}/composer
COPY --from=composer /usr/bin/composer /usr/local/bin/_composer
RUN \
    apk --no-cache add git subversion mercurial unzip \
    && \
    mkdir -p $COMPOSER_HOME \
    && \
    chown -R ${APP_USER}:${APP_GROUP} $COMPOSER_HOME

COPY --from=psalm /root/.composer /opt/composer

COPY resources/docker/php/php.ini /usr/local/etc/php/
COPY resources/docker/php/bin/* /usr/local/bin/
RUN chmod +rx /usr/local/bin/*

VOLUME /app

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/docker-php-entrypoint"]
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public/", "public/index.php"]


##########
# mkacme #
##########
FROM alpine:3.13.2 as mkacme

RUN apk add --no-cache curl

ARG STEP_VERSION=0.15.14
ARG STEP_CA_VERSION=0.15.11

RUN \
    curl -L https://github.com/smallstep/cli/releases/download/v${STEP_VERSION}/step_linux_${STEP_VERSION}_amd64.tar.gz \
        | tar xz -C /usr/bin/ --strip=2 step_${STEP_VERSION}/bin/step \
    && \
    curl -L https://github.com/smallstep/certificates/releases/download/v${STEP_CA_VERSION}/step-ca_linux_${STEP_CA_VERSION}_amd64.tar.gz \
        | tar xz -C /usr/bin/ --strip=2 step-ca_${STEP_CA_VERSION}/bin/step-ca \
    && \
    chmod +x /usr/bin/step /usr/bin/step-ca

RUN \
    mkdir -p /root/.step/secrets \
    && \
    sh -c "echo 'password' > /root/.step/secrets/password-file" \
    && \
    step ca init \
        --name traefik-cert-provider \
        --address 127.0.0.1:8443 \
        --provisioner traefik-cert-provider@example.com \
        --dns 127.0.0.1 \
        --password-file /root/.step/secrets/password-file \
    && \
    step ca provisioner add letsencrypt --type ACME \
    && \
    cp /root/.step/certs/root_ca.crt /usr/local/share/ca-certificates/ \
    && \
    update-ca-certificates

ARG TRAEFIK_VERSION
RUN \
    curl -L https://github.com/traefik/traefik/releases/download/v${TRAEFIK_VERSION}/traefik_v${TRAEFIK_VERSION}_linux_amd64.tar.gz \
        | tar xz -C /usr/bin/ traefik \
    && \
    chmod +x /usr/bin/traefik

COPY resources/docker/mkacme/entrypoint.sh /
RUN chmod +rx /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["printacme"]
