FROM php:8.1-fpm

# https://github.com/mlocati/docker-php-extension-installer

RUN apt-get update && apt-get upgrade -y

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions mongodb-stable
RUN install-php-extensions redis-stable
RUN install-php-extensions opcache-stable

RUN install-php-extensions mysqli pdo_mysql sqlite3
RUN install-php-extensions opcache memcache memcached
RUN install-php-extensions imagick gd
RUN install-php-extensions mbstring curl intl mcrypt xml xmlrpc xsl yaml zip ldap pcntl

RUN install-php-extensions @composer
