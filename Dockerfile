FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    python3 \
    python3-pip \
    && docker-php-ext-install pdo pdo_mysql zip

RUN pip3 install pandas scikit-learn joblib --break-system-packages

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080

CMD php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}