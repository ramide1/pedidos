FROM dunglas/frankenphp:php8.5-alpine
RUN apk update
RUN apk add --no-cache nodejs-lts
RUN install-php-extensions \
	pdo_mysql \
	gd \
	intl \
	zip \
	opcache
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/^memory_limit = -1/memory_limit = 4G/' "$PHP_INI_DIR/php.ini"
RUN sed -i 's/^; max_input_vars = 1000/max_input_vars = 5000/' "$PHP_INI_DIR/php.ini"
WORKDIR /app
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install
RUN npm run build