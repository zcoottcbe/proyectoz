FROM php:7.3-fpm-alpine

# Instalar dependencias
RUN docker-php-ext-install pdo_mysql

# Copiar el espacio de almacenamiento de Laravel
COPY --chown=www-data . /app

