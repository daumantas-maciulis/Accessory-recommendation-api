#!/bin/bash
echo "installing dependencies from Composer"
composer install

echo "Creating database"
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load