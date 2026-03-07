FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    COMPOSER_ALLOW_SUPERUSER=1 \
    CI_ENVIRONMENT=production

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" intl mbstring pdo_pgsql pgsql opcache \
    && a2enmod rewrite headers \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.* ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-progress

COPY . .

RUN mkdir -p writable/cache writable/logs writable/session \
    && chown -R www-data:www-data writable \
    && chmod -R 775 writable

COPY docker/render-start.sh /usr/local/bin/render-start.sh
RUN chmod +x /usr/local/bin/render-start.sh

EXPOSE 10000

CMD ["/usr/local/bin/render-start.sh"]
