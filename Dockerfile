# Usa a imagem oficial do PHP com extensões necessárias
FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar e habilitar Redis
RUN pecl install redis && docker-php-ext-enable redis

# Define o diretório de trabalho
WORKDIR /var/www

# Copia os arquivos do Laravel para o container
COPY . .

# Dá permissão de escrita no storage e bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expõe a porta 9000 para comunicação com o Nginx
EXPOSE 9000

CMD ["php-fpm"]
