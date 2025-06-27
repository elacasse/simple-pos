ARG UBUNTU_VERSION=24.04
ARG PHP_VERSION=8.3

FROM ubuntu:${UBUNTU_VERSION} AS ubuntu

FROM ubuntu AS base

# Set timezone (for tzdata)
ENV TZ=America/Toronto
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Remove the Ubuntu Pro/ESM advertisements
RUN dpkg-divert --divert /etc/apt/apt.conf.d/20apt-esm-hook.conf.bak --rename --local /etc/apt/apt.conf.d/20apt-esm-hook.conf

# Add ondrej repository (for php versions)
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get dist-upgrade -yq
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends software-properties-common gpg-agent
RUN apt-add-repository ppa:ondrej/php
RUN add-apt-repository ppa:ondrej/nginx
RUN apt-get update

# Install build tools
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends build-essential

# Install most used locales
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends locales && \
    locale-gen en_US && \
    locale-gen en_US.utf8 && \
    locale-gen en_CA && \
    locale-gen en_CA.utf8 && \
    locale-gen fr_CA && \
    locale-gen fr_CA.utf8

# Install common tools
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends git curl sudo ssh ca-certificates unzip ruby-full make gcc zlib1g-dev

# Clean up APT cache
RUN apt-get autoremove -yq
RUN apt-get clean -yq
RUN rm -rf /var/lib/apt/lists/*

FROM base AS step-nginx-php

ARG PHP_VERSION

# Install nginx, php, etc...
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends nginx
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-fpm
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-imap
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-mysql
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-sqlite3
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-gd
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-intl
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-mbstring
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-bcmath
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-curl
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-cli
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-xml
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-zip
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends php${PHP_VERSION}-odbc

RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/*
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

FROM step-nginx-php AS step-node

RUN apt-get update
RUN curl -fsSL https://deb.nodesource.com/setup_current.x | sudo -E bash -
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends nodejs

RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/*

FROM step-node AS step-config

# Install Supervisor
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends supervisor


# Laravel will be mounted at /var/www/api
WORKDIR /var/www

# Laravel will be served from /var/www/api
# Vue from /var/www/web

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]