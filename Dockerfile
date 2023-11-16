   FROM php:7.4-apache

   COPY index.php /var/www/html/

   RUN docker-php-ext-install mysqli

   EXPOSE 80