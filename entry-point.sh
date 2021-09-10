#!/bin/bash

php /app/bin/console doctrine:database:create --if-not-exists
php /app/bin/console doctrine:migration:diff -n
php /app/bin/console doctrine:migration:migrate -n

apachectl -D FOREGROUND
