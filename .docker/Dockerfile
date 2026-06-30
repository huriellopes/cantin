# Imagem de produção (PHP 8.4 — requisito do Laravel 13).
FROM php:8.4-fpm-alpine AS app

# Metadados da imagem
LABEL image.authors.name="Huriel Lopes"
LABEL image.authors.email="huriellopes1996@gmail.com"

# Define o diretório de trabalho dentro do contêiner.
WORKDIR /app

# Instala o composer e pacote para paralelismo
RUN set -eux; \
  ln -s /var/www /app  && \
  \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer && \
  \
  # Instala e configura timezone
  apk add --no-cache --virtual .fetch-deps \
  && apk del --no-network .fetch-deps

# Dependências para compilação das extensões
RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
            $PHPIZE_DEPS \
            freetype-dev \
            libzip-dev \
            postgresql-dev \
            icu-dev && \
        \
        docker-php-ext-configure gd --with-freetype && \
        docker-php-ext-configure pdo_pgsql --with-pdo-pgsql && \
        docker-php-ext-configure zip && \
        \
        docker-php-ext-install -j$(nproc) \
            gd \
            intl \
            bcmath \
            zip \
            pdo_pgsql \
            opcache && \
        \
        runDeps="$( \
            scanelf --needed --nobanner --format '%n#p' --recursive /usr/local \
                | tr ',' '\n' \
                | sort -u \
                | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
        )" && \
        apk add --no-cache $runDeps && \
        \
        apk del --no-network .build-deps

# Instala dependências necessárias incluindo supervisor
RUN apk add --no-cache \
    npm \
    supervisor \
    bash

# Adiciona o arquivo de configuração do OPcache
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Limites de upload do PHP (editor rico). Alinhado ao nginx e ao config/editor.php.
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Configuração do Supervisor
RUN mkdir -p /etc/supervisor/conf.d
COPY supervisord.conf /etc/supervisor/supervisord.conf

# Cria diretório para logs do supervisor
RUN mkdir -p /var/log/supervisor

EXPOSE 9000

# Inicia tanto o PHP-FPM quanto o Supervisor
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
