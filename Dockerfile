FROM dunglas/frankenphp:latest-php8.3

# Базовый образ уже включает pdo_sqlite, sqlite3, mbstring, ctype, fileinfo.
# Устанавливаем pdo_pgsql для работы с PostgreSQL и opcache для производительности.
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Рабочая директория
WORKDIR /app

# 4. Копирование исходного кода
COPY . /app

# 5. Права на storage и bootstrap/cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# 6. Конфигурация FrankenPHP: worker-режим задаётся через Caddyfile (/etc/caddy/Caddyfile)
ENV SERVER_NAME=":80"

COPY docker/caddy/Caddyfile /etc/caddy/Caddyfile

EXPOSE 80 443 443/udp

ENTRYPOINT ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
