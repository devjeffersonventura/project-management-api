# Project Management API

API de gerenciamento de projetos e tarefas desenvolvida com Laravel 11.

## 🚀 Tecnologias

- PHP 8.3
- Laravel 11
- PostgreSQL
- Docker
- Nginx
- Swagger/OpenAPI
- Mailtrap (para emails em desenvolvimento)

## 📋 Pré-requisitos

- Docker Desktop
- Git
- Conta no Mailtrap (para testes de email)

## ⚙️ Instalação com Docker

1. Clone o repositório
```bash
git clone https://github.com/devjeffersonventura/project-management-api
cd project-management-api
```

2. Configure o ambiente
```bash
# Copie o arquivo de ambiente
cp .env.example .env
```

3. Construa e inicie os containers
```bash
# Construir os containers
docker-compose build --no-cache

# Iniciar os containers em background
docker-compose up -d
```

4. Configure a aplicação
```bash
# Entre no container do backend
docker-compose exec backend bash

# Dentro do container:
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
```

## 🐳 Arquivos de Configuração Docker

### docker-compose.yml
```yaml
services:
  # Backend Laravel
  backend:
    build:
      # ATENÇÃO: Altere este caminho para o local do seu projeto
      context: /caminho/completo/para/seu/projeto
      dockerfile: docker/backend/Dockerfile
    container_name: app_backend
    restart: unless-stopped
    working_dir: /var/www
    environment:
      QUEUE_CONNECTION: database
    volumes:
      # ATENÇÃO: Altere este caminho para o local do seu projeto
      - /caminho/completo/para/seu/projeto:/var/www
    networks:
      - app_network

  # Nginx Service
  nginx:
    image: nginx:alpine
    container_name: app_nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      # ATENÇÃO: Altere estes caminhos para o local do seu projeto
      - /caminho/completo/para/seu/projeto:/var/www
      - /caminho/completo/para/seu/projeto/docker/nginx/conf.d:/etc/nginx/conf.d
    networks:
      - app_network

  # PostgreSQL Service
  db:
    image: postgres:15-alpine
    container_name: app_db
    restart: unless-stopped
    environment:
      POSTGRES_DB: ${DB_DATABASE}
      POSTGRES_PASSWORD: ${DB_PASSWORD}
      POSTGRES_USER: ${DB_USERNAME}
    volumes:
      - dbdata:/var/lib/postgresql/data
    ports:
      - "5432:5432"
    networks:
      - app_network

networks:
  app_network:
    driver: bridge

volumes:
  dbdata:
```

### Dockerfile (Backend)
```dockerfile
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

# Instalar extensões PHP
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Configurar Supervisor
RUN mkdir -p /var/log/supervisor /var/run/supervisor
```

### Nginx Configuration
```nginx
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass backend:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

### Supervisor Configuration
```ini
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisor/supervisord.pid
childlogdir=/var/log/supervisor

[program:laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/worker.log
stopwaitsecs=3600
```

## 🔧 Serviços Disponíveis

- **API**: http://localhost:8000
- **Banco de Dados**: localhost:5432
- **Queue Worker**: Roda automaticamente via Supervisor

## 📦 Comandos Docker Úteis

### Gerenciamento de Containers
```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f
```

### Banco de Dados
```bash
# Acessar PostgreSQL
docker-compose exec db psql -U postgres -d projectmanagement_db

# Executar migrações
docker-compose exec backend php artisan migrate

# Resetar banco
docker-compose exec backend php artisan migrate:fresh
```

### Gerenciamento de Filas
```bash
# Verificar status dos workers
docker-compose exec backend supervisorctl status

# Reiniciar workers
docker-compose exec backend supervisorctl restart laravel-queue:*

# Ver logs das filas
docker-compose exec backend tail -f /var/log/supervisor/worker.log
```

## 🧪 Testes

### Executando os Testes
```bash
# Dentro do container backend
docker-compose exec backend php artisan test
```

### Estrutura de Testes

#### Testes de Integração (Feature)
- `UserRegistrationTest`: Valida o fluxo de registro de usuários
  - Criação de usuário
  - Validação de campos
  - Persistência no banco
  - Estrutura da resposta

#### Testes Unitários (Unit)
- `ProjectProgressCalculatorTest`: Testa cálculos de progresso do projeto
- `ProjectDurationCalculatorTest`: Testa cálculos de duração do projeto

## 🔍 Troubleshooting

### Problemas com Banco de Dados
```bash
# Verificar status do banco
docker-compose ps db

# Ver logs do banco
docker-compose logs db
```

### Problemas com Permissões
```bash
# Corrigir permissões de storage
docker-compose exec backend chown -R www-data:www-data /var/www/storage
```

### Problemas com Workers
```bash
# Verificar logs do supervisor
docker-compose exec backend tail -f /var/log/supervisor/supervisord.log
```

## 🧹 Limpeza

Para remover todos os containers e volumes:
```bash
# Parar e remover containers
docker-compose down

# Remover volumes
docker-compose down -v

# Remover containers/imagens não utilizados
docker system prune -a
```

## 📝 Variáveis de Ambiente

Principais variáveis necessárias no `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=projectmanagement_db
DB_USERNAME=postgres
DB_PASSWORD=admin

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_UI_PERSIST_AUTHORIZATION=true
```

## 📚 Documentação API

Acesse a documentação Swagger em:
```
http://localhost:8000/api/documentation
```
