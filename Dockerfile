FROM dunglas/frankenphp:php8.5-alpine
RUN apk update
RUN apk add --no-cache nodejs-lts npm
RUN install-php-extensions \
	pdo_mysql \
	gd \
	intl \
	zip \
	bcmath
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/^memory_limit = -1/memory_limit = 4G/' "$PHP_INI_DIR/php.ini"
RUN sed -i 's/^; max_input_vars = 1000/max_input_vars = 5000/' "$PHP_INI_DIR/php.ini"
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
RUN npm install
RUN npm run build
ARG USER=appuser
RUN \
	adduser -D ${USER}; \
	setcap -r /usr/local/bin/frankenphp; \
	chown -R ${USER}:${USER} /config/caddy /data/caddy
USER ${USER}
CMD ["frankenphp", "php-server", "--root=/app/public", "--listen=:8080"]