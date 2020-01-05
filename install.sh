#!/bin/bash
file=./.env
if [ ! -e "$file" ]; then
    echo "Primo avvio? Configuro l'app"
    docker-compose up -d
    cp ./.env.example ./.env
    touch ./database/database.sqlite
    docker-compose exec php-fpm composer install && php artisan key:generate && php artisan migrate:fresh --seed && php vendor/bin/phpunit
fi

echo "Visita http://localhost:8000"