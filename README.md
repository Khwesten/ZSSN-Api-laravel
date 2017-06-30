# ZSSN Api - laravel

## Requirements

- PHP >= 7.0

## Getting Started
Enter on api folder

    composer install
    
Create the *.env* file, use as example the *.env.example* file and create your database

Remember to install all dependencies from laravel(https://laravel.com/docs/5.4/installation#installation)

Run migrations:
 
    php artisan migrate

Run seeds to insert basic items:
 
    php artisan db:seed

Run server:
 
    php artisan serve
    
Swagger Documentation on:

    http://localhost:8000/swagger/
    
To see tests coverage:

    "vendor/bin/phpunit"
    
If you have any problems with *cipher*, run

    php artisan key:generate


