# Project Management API

API de gerenciamento de projetos e tarefas desenvolvida com Laravel 11.

## 🚀 Tecnologias

- PHP 8.2
- Laravel 11
- MySQL
- Swagger/OpenAPI
- Mailtrap (para emails em desenvolvimento)

## 📋 Pré-requisitos

- PHP >= 8.2
- Composer
- MySQL
- Git
- Conta no Mailtrap (para testes de email)

## ⚙️ Instalação

1. Clone o repositório
```bash
git clone https://github.com/devjeffersonventura/project-management-api
cd project-management-api
```
2. Instale as dependências
```bash
composer install
```

3. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure o banco de dados no arquivo `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. Configure o Swagger no `.env`
```env
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_UI_PERSIST_AUTHORIZATION=true
```

6. Configure o Mailtrap no `.env`
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_username_mailtrap
MAIL_PASSWORD=seu_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@exemplo.com
MAIL_FROM_NAME="${APP_NAME}"
```

7. Execute as migrations
```bash
php artisan migrate
```

8. Gere a documentação do Swagger
```bash
php artisan l5-swagger:generate
```

9. Inicie o servidor
```bash
php artisan serve
```

10. Inicie o worker que processa as job queues
```bash
php artisan queue:work
```
