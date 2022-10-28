#!/bin/bash

composer install --no-scripts --prefer-dist --no-interaction

php /app/bin/console doctrine:database:create --if-not-exists
php /app/bin/console doctrine:migration:diff -n
php /app/bin/console doctrine:migration:migrate -n

chmod 777 -R /app/var
php /app/bin/console cache:clear

apachectl -D FOREGROUND
