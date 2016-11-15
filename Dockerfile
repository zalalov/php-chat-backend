FROM php:7.0-apache
COPY /src /var/www/html/

RUN a2enmod rewrite
RUN /etc/init.d/apache2 restart