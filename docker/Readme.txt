1) Create folder "databases" in docker directory.
2) Create .env file from .env.example
3) Run start.sh
4) Go to 127.0.0.1:6080 And create database
5) Setup your laravel .env to
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ibeacons
DB_USERNAME=root
DB_PASSWORD=123456789

P.S. For execute artisan commands run artisaner.sh
