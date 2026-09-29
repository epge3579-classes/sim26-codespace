FROM php:8.4-fpm-bookworm

ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=1000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       ca-certificates \
       curl \
       default-mysql-client \
       git \
       sudo \
       unzip \
       zip \
    && docker-php-ext-install pdo_mysql mysqli \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && groupadd --gid ${USER_GID} ${USERNAME} \
    && useradd --uid ${USER_UID} --gid ${USER_GID} -m ${USERNAME} \
    && echo "${USERNAME} ALL=(root) NOPASSWD:ALL" > /etc/sudoers.d/${USERNAME} \
    && chmod 0440 /etc/sudoers.d/${USERNAME} \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/99-xdebug-settings.ini

WORKDIR /workspace
