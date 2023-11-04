FROM php:7.4-apache

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER 1

# Fix apache
RUN a2enmod rewrite

# Install OS dependencies
RUN apt-get update
RUN apt-get install -y git nodejs npm libzip-dev zip unzip

# Install PHP dependencies
RUN docker-php-ext-install pdo pdo_mysql zip

# Install composer
RUN curl -sSk --retry 3 https://getcomposer.org/installer | php -- --disable-tls --install-dir=/usr/local/bin --filename=composer

# Install Bower
RUN npm install -g bower

# Copy files
COPY . .

# Install PHP dependencie
RUN composer install --no-plugins --no-scripts

# Install JS dependencies
RUN bower install --allow-root

# Fix permissions
RUN chown -R www-data:www-data /var/www

EXPOSE 80
