FROM php:8.1-apache
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY . /var/www/html
RUN apt-get update && apt-get install zip unzip
RUN docker-php-ext-install mysqli pdo pdo_mysql
ENV DB_HOST=mysql
ENV DB_USER=sara
ENV DB_PASS=1234
ENV DB_NAME=Foro
ENV ADMIN_NAME=admin
ENV ADMIN_SURNAME=admin
ENV ADMIN_EMAIL=admin@admin.com
ENV ADMIN_PASS=Daw1234!
ENV ADMIN_AGE=25
ENV ADMIN_TYPE=admin
ENV ADMIN_BLOCK=unblock
RUN composer install
RUN composer du