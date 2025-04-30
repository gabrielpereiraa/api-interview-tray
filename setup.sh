#!/bin/bash

echo "mounting app and db containers..."
docker-compose up -d --build

echo "waiting for MySQL container..."
until docker-compose exec db mysql -uroot -proot -e "SELECT 1;" &> /dev/null
do
  echo -n "."
  sleep 1
done

echo "creating databases..."
docker-compose exec db mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS db_interview_tray;"
docker-compose exec db mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS db_interview_tray_test;"

echo "reset cache..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear

echo "migrations..."
docker-compose exec app php artisan migrate --database=mysql
docker-compose exec app php artisan migrate --database=mysql_testing

echo "seeders..."
docker-compose exec app php artisan db:seed --database=mysql

echo "tests..."
docker-compose exec app php artisan test

echo "JWT Secret..."
docker-compose exec app php artisan jwt:secret

echo "Script finalizado!"
