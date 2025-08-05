FROM php:8.2-fpm

RUN apt-get update -y \
    && apt-get install -y \
    postgresql-client \
    openssl \
    npm \
    zip \
    unzip \
    git \
    curl \
    wget \
    vim \
    supervisor \
    libpq-dev  \
    libonig-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql mbstring pcntl \
    && rm -rf /var/lib/apt/lists/* 


WORKDIR /app

RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN alias composer='/usr/local/bin/composer'

WORKDIR /var/www/html

RUN git clone https://github.com/LAwade/dashboardbt

WORKDIR /var/www/html/dashboardbt

RUN cp .env.example .env

## ATRIBUINDO PERMISSÕES NOS FILES
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

RUN npm install
RUN composer install 

RUN php artisan storage:link
RUN php artisan key:generate

RUN npm run build

RUN cp supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8181 5173 6001

CMD ["/usr/bin/supervisord"]
