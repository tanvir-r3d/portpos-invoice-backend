# Invoice Generator

[![Coverage Status](https://coveralls.io/repos/github/slimphp/Slim-Skeleton/badge.svg?branch=master)](https://coveralls.io/github/slimphp/Slim-Skeleton?branch=master)

Requirements:

- php8

Run this command from the root directory of project.

```bash
composer install
```

Copy `.env` from `.env.example` and setup database settings:

```bash
cp .env.example .env
```

### Generate Private and Public Keys [ which are already present]

```bash
   ssh-keygen -t rsa -b 4096 -m PEM -f private.pem
   openssl rsa -in private.pem -pubout -outform PEM -out public.pem
```

### update .env

```bash
    JWT_EXPIRES_IN_DAY=3
    JWT_PRIVATE_KEY_PATH=/path/to/private.pem
    JWT_PUBLIC_KEY_PATH=/path/to/public.pem
    JWT_PASS_PHRASE=
```

#### Please setup `phinx.php` for database migration and seed

```bash
php vendor/bin/phinx migrate
php vendor/bin/phinx seed:run
```

Finally run `composer start` to run project.

### Implemented API ENDPOINTS

```bash
   - Admin
        1. [POST] 
            /api/v1/login
        2. [POST]
            /api/v1/logout
   - Order
        2. [GET] 
            /api/v1/orders
        3. [POST]  
            /api/v1/orders
        4. [PATCH]
            /api/v1/orders/status/{id}/{status}
        5. [GET]
            /api/v1/orders/ipn/{id}
```