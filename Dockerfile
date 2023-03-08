FROM php:8.1-apache

RUN a2enmod rewrite expires

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt update \
	&& apt install -y default-mysql-client \
	&& apt install -y sendmail \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

RUN curl -o /bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
	&& chmod +x /bin/wp \
	&& wp --info --allow-root

ADD ./php.ini /usr/local/etc/php/php.ini

# ENTRYPOINT resets CMD now
CMD ["apache2-foreground"]

COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
RUN chmod +x /entrypoint.sh

COPY src/ /var/www/html/
