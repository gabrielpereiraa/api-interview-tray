#!/bin/bash

echo ".env files..."
cp .env.example .env
cp .env.example .env.testing
sed -i 's/^APP_ENV=.*/APP_ENV=testing/' .env.testing
sed -i 's/^DB_DATABASE=.*/DB_DATABASE=db_interview_tray_test/' .env.testing

echo "mounting app and db containers..."
docker-compose up -d --build
printf "\n"

echo "APP Key..."
docker-compose exec app php artisan key:generate
printf "\n"

echo "fixing permissions..."
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
printf "\n"

echo "waiting for MySQL container..."
until docker-compose exec db mysql -uroot -proot -e "SELECT 1;" &> /dev/null
do
  echo -n "."
  sleep 1
done
printf "\n"

echo "creating databases..."
docker-compose exec db mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS db_interview_tray;"
docker-compose exec db mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS db_interview_tray_test;"
printf "\n"

echo "reset cache..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
printf "\n"

echo "migrations..."
docker-compose exec app php artisan migrate
docker-compose exec app php artisan migrate --env=testing
printf "\n"

echo "seeders..."
docker-compose exec app php artisan db:seed --database=mysql
printf "\n"

echo "JWT Secret..."
docker-compose exec app php artisan jwt:secret
docker-compose exec app php artisan jwt:secret --env=testing
printf "\n"

echo "tests..."
docker-compose exec app php artisan test --env=testing
printf "\n"

echo "Script finalizado!"