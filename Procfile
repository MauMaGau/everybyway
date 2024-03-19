web: heroku-php-apache2 public/
release: php artisan migrate --force && php artisan cache:clear && php artisan config:cache && composer install && npm run build
