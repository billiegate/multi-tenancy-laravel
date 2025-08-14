FROM php:latest

# Install unzip
RUN apt update && apt install -y unzip

# Install Composer using another strategy but with the same result
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the environment variables
ENV HOME="/root"
ENV PATH="/root/.composer/vendor/bin:${PATH}:/root/.bun/bin"

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash

# Create some symlinks to the bun executable
RUN ln -s $(which bun) /usr/local/bin/npm
RUN ln -s $(which bunx) /usr/local/bin/npx

# Install Laravel Installer
RUN composer global require laravel/installer

# Install pcntl extension
RUN docker-php-ext-install pcntl

WORKDIR /app

# copy project
COPY . .
RUN cp .env.example .env
RUN touch ./database/database.sqlite

# set up project
RUN composer install
RUN php artisan key:generate
RUN php artisan migrate --path=database/migrations/landlord --database=landlord --force
# RUN php artisan optimize:clear

RUN npm install
RUN npm run build

EXPOSE 8000

ENTRYPOINT [ "php", "artisan", "serve", "--host", "0.0.0.0" ]