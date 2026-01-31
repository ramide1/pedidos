FROM dunglas/frankenphp:php8.5-alpine
RUN apk update
RUN install-php-extensions \
	pdo_mysql \
	gd \
	intl \
	zip \
	bcmath \
	pdo_pgsql
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/^memory_limit = 128M/memory_limit = 4G/' "$PHP_INI_DIR/php.ini"
RUN sed -i 's/^; max_input_vars = 1000/max_input_vars = 5000/' "$PHP_INI_DIR/php.ini"
RUN curl -fsSL https://bun.sh/install | bash
ENV PATH="/root/.bun/bin:$PATH"
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
RUN bun install
RUN bun run build
ARG USER=appuser
RUN \
	adduser -D ${USER}; \
	setcap -r /usr/local/bin/frankenphp; \
	chown -R ${USER}:${USER} /config/caddy /data/caddy /app
USER ${USER}
CMD ["frankenphp", "php-server", "--root=/app/public", "--listen=:8080"]