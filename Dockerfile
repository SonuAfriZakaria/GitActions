FROM php:8.2-cli
RUN apt-get update && apt-get install -y git curl zip unzip openssh-client
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer global require "laravel/envoy"
ENV PATH="~/.composer/vendor/bin:${PATH}"
