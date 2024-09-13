FROM php:8.1-apache

ENV SSH_PASSWD "root:Docker!"

# PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
#        libicu52 \
        zlib1g \
        libpq-dev \
        libpng-dev \
        libicu-dev \
        zlib1g-dev \
        libxrender1 \
        libfontconfig1 \
        libx11-dev \
        libxext-dev \
		openssh-server \
        libevent-dev \
        libssl-dev \
		libzip-dev\
        wkhtmltopdf\
        nano \
        cron \
    && echo "$SSH_PASSWD" | chpasswd \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install \
        intl \
#        mbstring \
        # pdo_mysql \
        pdo \
        pdo_pgsql \
        zip \
        bcmath \
        gd \
#        sockets \
        pcntl \
	sysvsem \
    && apt-get purge -y --auto-remove $buildDeps

# Apache config
RUN a2enmod rewrite
RUN a2enmod headers
ADD docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
ADD docker/apache/ports.conf /etc/apache2/ports.conf

# RUN sed -i "s/%PORT%/${PORT}/g" /etc/apache2/sites-available/000-default.conf
# RUN sed -i "s/%PORT%/${PORT}/g" /etc/apache2/ports.conf

# PHP config
ADD docker/php/php.ini /usr/local/etc/php/php.ini


COPY .env .env

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD . /var/www/html

RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-progress

RUN chown -Rf www-data:www-data /var/www/html/var

#ssh access
COPY /docker/sshd_config /etc/ssh/

EXPOSE 8080 2222
CMD ["/var/www/html/docker/start.sh"]