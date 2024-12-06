# About project
Blog

## Setup instructions:
- docker
- composer update
- .env (SESSION_DRIVER=file, DB_CONNECTION=mysql, DB_HOST=db, DB_PORT=3306, DB_DATABASE=blog, DB_USERNAME=root, DB_PASSWORD=root)
- php artisan migrate
- php artisan db:seed
