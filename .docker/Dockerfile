FROM php:8.2-fpm-alpine3.17

LABEL image.authors.name="Huriel Lopes"
LABEL image.authors.email="huriellopes1996@gmail.com"

WORKDIR /app

# Instala o composer e pacote para paralelismo
RUN set -eux; \
  ln -s /var/www /app  && \
  \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer && \
  \
  # Instala e configura timezone
  apk add --no-cache --virtual .fetch-deps && \
  apk del --no-network .fetch-deps

# Dependências para compilação das extensões
RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
            $PHPIZE_DEPS \
            freetype-dev \
            libzip-dev \
            postgresql-dev && \
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
            pdo_pgsql && \
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

EXPOSE 9000

CMD ["php-fpm"]
