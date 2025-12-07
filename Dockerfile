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
    && docker-php-ext-install -j"$(nproc)" intl gd zip mysqli soap xml mbstring curl opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Build argument to choose Moodle branch (for example: MOODLE_404_STABLE, main, etc.)
ARG MOODLE_BRANCH=MOODLE_404_STABLE

# Fetch Moodle from official Git mirror
RUN rm -rf ./* \
    && git clone --depth 1 -b "$MOODLE_BRANCH" https://github.com/moodle/moodle.git /var/www/html

# Create moodledata directory for file storage
RUN mkdir -p /var/www/moodledata \
    && chown -R www-data:www-data /var/www/html /var/www/moodledata

# Expose Apache HTTP port
EXPOSE 80

# Use default Apache start command
CMD ["apache2-foreground"]
