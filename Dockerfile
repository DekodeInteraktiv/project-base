FROM dunglas/frankenphp

#ENV SERVER_NAME=general.dev
ENV SERVER_NAME=:80

RUN install-php-extensions \
	pdo_mysql \
	gd \
	intl \
	zip \
	opcache \
	mbstring \
	mysqli \
	redis \
	imagick \
	exif

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy the PHP files of your project in the public directory
#COPY . /app
