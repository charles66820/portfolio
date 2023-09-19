FROM composer:1.4 as composer
FROM php:7.2-apache

ARG WEB_USER=www-data
ARG WEB_PRJ_DIR=/var/www/prj/

RUN apt-get update
RUN apt-get install -y git zip

# Configure apache2
RUN sed -ri -e 's!/var/www/html!/var/www/prj/public\n\n<Directory /var/www/prj/public>\nAllowOverride All\nOrder Allow,Deny\nAllow from All\n</Directory>!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!#ServerName www.example.com!ServerName www.example.com!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!ServerAdmin webmaster@localhost!ServerAdmin support@magicorp.fr!g' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy projet and start composer install (vendor install)
USER ${WEB_USER}
COPY --chown=${WEB_USER}:${WEB_USER} . ${WEB_PRJ_DIR}
WORKDIR ${WEB_PRJ_DIR}
RUN COMPOSER_CACHE_DIR=${WEB_PRJ_DIR}.composer composer install --optimize-autoloader
# --no-dev

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
