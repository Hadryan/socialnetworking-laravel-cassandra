chmod 777 -R /var/www/localhost/htdocs/storage && \
chmod 777 -R /var/www/localhost/htdocs/bootstrap/cache

crond -l 2 -f &


php-fpm7

php /var/www/localhost/htdocs/artisan command:common-friends

nginx

