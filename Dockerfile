FROM php:8.1-apache

# Install system dependencies and PHP extensions required by Moodle
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       git \
       unzip \
       libicu-dev \
       libxml2-dev \
       libpng-dev \
       libjpeg-dev \
       libfreetype6-dev \
       libzip-dev \
       libcurl4-openssl-dev \
       libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" intl gd exif zip mysqli soap xml mbstring curl opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# PHP configuration for Moodle (input limits, opcache)
RUN { \
    echo 'max_input_vars=5000'; \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.save_comments=1'; \
} > /usr/local/etc/php/conf.d/moodle.ini

# Set working directory
WORKDIR /var/www/html

# Build argument to choose Moodle branch (for example: MOODLE_404_STABLE, main, etc.)
ARG MOODLE_BRANCH=MOODLE_404_STABLE

# Fetch Moodle from official Git mirror
RUN rm -rf ./* \
    && git clone --depth 1 -b "$MOODLE_BRANCH" https://github.com/moodle/moodle.git /var/www/html

# Copy local plugins provided by this repository into the Moodle codebase
COPY local/ /var/www/html/local/

# Create moodledata directory for file storage
RUN mkdir -p /var/www/moodledata \
    && chown -R www-data:www-data /var/www/html /var/www/moodledata

# Copy custom entrypoint to adjust config.php (dbtype, wwwroot, reverse proxy flags)
COPY moodle-entrypoint.sh /usr/local/bin/moodle-entrypoint.sh
RUN chmod +x /usr/local/bin/moodle-entrypoint.sh

# Expose Apache HTTP port
EXPOSE 80

# Use custom entrypoint then start Apache
ENTRYPOINT ["moodle-entrypoint.sh"]
CMD ["apache2-foreground"]
