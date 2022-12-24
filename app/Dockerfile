FROM php:8.1-fpm AS php_fpm

RUN apt-get update && \
    apt-get -y install zip libfreetype6-dev libjpeg62-turbo-dev libpng-dev libpq-dev libxslt-dev libzip-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j$(nproc) bcmath gd intl opcache pdo_pgsql xsl zip && \
    pecl install apcu && \
    docker-php-ext-enable apcu && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG APP_USER
RUN useradd -m -s /bin/bash -u 1000 $APP_USER && \
    chown $APP_USER:$APP_USER -R /var/www && \
    sed -i -e "s/www-data/$APP_USER/g" /usr/local/etc/php-fpm.d/www.conf

COPY docker/app/entrypoint.sh /usr/local/bin/docker-app-entrypoint
RUN chmod +x /usr/local/bin/docker-app-entrypoint

ENTRYPOINT ["docker-app-entrypoint"]
CMD ["php-fpm"]



FROM php_fpm AS app_dev

ENV APP_ENV=dev

RUN pecl install pcov xdebug && \
    docker-php-ext-enable pcov && \
    docker-php-ext-enable xdebug

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini && \
    rm $PHP_INI_DIR/php.ini-production

ARG APP_USER
USER $APP_USER



FROM php_fpm AS app_prod

ENV APP_ENV=prod

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini && \
    rm $PHP_INI_DIR/php.ini-development

ARG APP_USER
USER $APP_USER

COPY --chown=$APP_USER . .
RUN rm -rf docker/ && \
    composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress && \
    composer clear-cache && \
    composer dump-autoload --classmap-authoritative --no-dev && \
    composer dump-env prod && \
    composer run-script --no-dev post-install-cmd



FROM nginx:1 AS proxy

ARG APP_USER
RUN useradd -m -s /bin/bash -u 1000 $APP_USER && \
    mkdir -p /var/www/html/public && \
    chown $APP_USER:$APP_USER -R /var/www/html && \
    sed -i -e "s/^user .*/user $APP_USER;/g" /etc/nginx/nginx.conf

VOLUME /var/www/html/public
WORKDIR /var/www/html/public

COPY --from=app_prod /var/www/html/public .
COPY docker/proxy/default.conf /etc/nginx/conf.d/default.conf
