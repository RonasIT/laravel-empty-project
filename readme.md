# Docker configuration

docker-compose содержит конфигурацию для PHP 7.1 с mysql, postgres, redis

Лишние сервисы на конкретном проекте можно удалить из docker-compose.yml

Для генерации пользователя с ролью админ и файлов .env и .env.testing, необходимо запустить команду php artisan init и следовать инструкциям