FROM php:8.3.6-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    supervisor

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto
COPY . .

# Instalar dependências do projeto
RUN composer install

# Definir permissões
RUN chown -R www-data:www-data /var/www

# Configurar Supervisor
RUN mkdir -p /var/log/supervisor
RUN mkdir -p /var/run/supervisor
COPY docker/backend/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Modificar o CMD para iniciar o supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"] 