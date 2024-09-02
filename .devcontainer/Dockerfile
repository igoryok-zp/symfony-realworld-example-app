FROM ubuntu:24.04

RUN apt-get update && \
    apt-get -y install \
        bash-completion \
        composer \
        git \
        php-apcu \
        php-bcmath \
        php-cli \
        php-curl \
        php-gd \
        php-gmp \
        php-imap \
        php-intl \
        php-json \
        php-mbstring \
        php-pcov \
        php-pgsql \
        php-pspell \
        php-tidy \
        php-xdebug \
        php-xml \
        php-zip \
        && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

ARG APP_USER
ARG APP_USER_ID
RUN userdel ubuntu && \
    useradd -m -s /bin/bash -u $APP_USER_ID $APP_USER && \
    mkdir -p /workspace && \
    chown $APP_USER:$APP_USER -R /workspace

USER $APP_USER

VOLUME /workspace
WORKDIR /workspace
