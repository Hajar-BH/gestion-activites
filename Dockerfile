FROM php:8.2-apache
WORKDIR /var/www/html
COPY . /var/www/html
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
COPY render-apache.sh /usr/local/bin/render-apache.sh
RUN chmod +x /usr/local/bin/render-apache.sh
CMD ["render-apache.sh"]
