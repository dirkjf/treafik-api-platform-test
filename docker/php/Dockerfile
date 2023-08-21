FROM php:8.2-fpm

WORKDIR /var/www/project

RUN apt-get update && apt-get install -y --no-install-recommends \
    git lsof grep nano \
    libpq-dev \
    zlib1g-dev \
    libxml2-dev \
    libpng-dev \
    libzip-dev \
    curl debconf subversion git apt-transport-https apt-utils \
    build-essential locales acl mailutils wget nodejs npm zip unzip \
    gnupg gnupg1 gnupg2

# Docker PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    soap \
    zip \
    opcache \
    gd \
    intl \
    zip \
    opcache

RUN npm install -g yarn

COPY ./opcache.ini /usr/local/etc/php/conf.d/
COPY ./app.ini /usr/local/etc/php/conf.d/

# install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer self-update 2.5.8

RUN usermod -u 1000 www-data
RUN usermod -a -G www-data root

RUN mkdir -p /var/www
RUN chown -R www-data:www-data /var/www

COPY --link ./docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY --link ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
