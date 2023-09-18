# Petshop API

## How to Install and Run the Project

- ```bash
    git clone https://github.com/bgreatfit/petshop.git
    ```
- Copy ```.env.example``` to ```.env```
- ```docker-compose build```
- ```docker compose up -d```
- Install Dependencies 
  ```bash
     docker-compose exec app composer install
  ```
- You can see the project on ```127.0.0.1:8080```

- Generate App key
```bash
    docker-compose exec app php artisan key:generate
```

- Generate JWT SECRET
```bash
   docker-compose exec app php artisan jwt:generate-keys
```

- Run Migrations and seeders
```bash
    docker-compose exec app php artisan migrate --seed
```

- Swagger docs is at http://localhost:8088/api/v1/documentation

- To run all tests:
```bash
    docker-compose exec app php artisan test
```

- Larastan is set at Level 8:
```bash
    docker-compose exec app composer analyse 
```
- Php insights:
```bash
    docker-compose exec app  composer insight
```
## Specifications
- PHP 8.2
- Laravel 10
- Swagger Documentation
- Feature and Unit Tests
- JWT Authentication
- Migration and Seeders
- Custom Middleware
- Docker Setup
- Larastan
- PHP Insights
