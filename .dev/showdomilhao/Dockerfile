FROM php:8.4-fpm-alpine

# Instalar dependências do sistema
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    icu-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    imagemagick \
    imagemagick-dev

# Instalar extensões
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_mysql \
        mysqli \
        mbstring \
        zip \
        gd \
        opcache

# Instalar a extensão Imagick
RUN pecl install imagick && docker-php-ext-enable imagick

# Instalara extensão Redis
RUN pecl install redis && docker-php-ext-enable redis

# Configurar OPcache para produção
#RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
#    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar usuário para evitar problemas de permissão
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

# Definir diretório de trabalho
WORKDIR /var/www/html

# Mudar proprietário dos arquivos
RUN chown -R www:www /var/www/html

RUN git config --global --add safe.directory /var/www/html

# Executar como usuário www
USER www

# Expor porta 9000 para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
