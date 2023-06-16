FROM php:8.2.4-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN curl curl -fsSL https://deb.nodesource.com/setup_14.x | bash -


RUN apt-get update
RUN apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ \
    libpng-dev libjpeg-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip build-essential libfreetype6-dev vim ssmtp mailutils

RUN apt-get install -y nodejs

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash -
RUN apt install symfony-cli

#RUN npm install -g npm@latest
RUN npm install -g yarn

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen
RUN echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen
RUN locale-gen

RUN curl -sS https://getcomposer.org/installer | php --
RUN mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql mysqli opcache intl zip calendar dom mbstring gd xsl
RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install pcov
RUN echo "extension=pcov.so" > /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini
COPY conf/app.ini /usr/local/etc/php/conf.d/docker-php-app.ini


# set up sendmail config, see http://linux.die.net/man/5/ssmtp.conf for options
RUN echo "hostname=localhost.localdomain" > /etc/ssmtp/ssmtp.conf
RUN echo "mailhub=maildev" >> /etc/ssmtp/ssmtp.conf

# Set up php sendmail config
RUN echo "sendmail_path=sendmail -i -t" >> /usr/local/etc/php/conf.d/php-sendmail.ini

RUN apt-get install -y supervisor
COPY conf/messenger-worker.conf /etc/supervisor/conf.d/

RUN a2enmod ssl && a2enmod rewrite
RUN mkdir -p /etc/apache2/ssl
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY ssl/*.pem /etc/apache2/ssl/

WORKDIR /var/www/

EXPOSE 80
EXPOSE 443
