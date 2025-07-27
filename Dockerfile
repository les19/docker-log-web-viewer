FROM dunglas/frankenphp

RUN install-php-extensions \
    @composer \
    pcntl \
    redis \
    mysqli \
    zlib \
    pdo_mysql

ENV APP_ENV=production
ENV APP_DEBUG=false

ENV LOG_CHANNEL=stack
ENV LOG_LEVEL=debug

ENV PHP_CLI_SERVER_WORKERS=4
ENV BCRYPT_ROUNDS=12

COPY . /app

RUN echo "APP_KEY=" >> /app/.env

RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate
RUN php artisan storage:link

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
