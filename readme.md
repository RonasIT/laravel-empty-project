# Laravel empty project

*Предустановленный laravel c включенным пакетом внутренних решений*

### Локальный запуск

docker-compose содержит конфигурацию для PHP 7.3 с postgres, redis

Для генерации пользователя с ролью админ и файлов .env и .env.testing, необходимо запустить команду php artisan init и следовать инструкциям

### Деплой приложения в kubernetes

Используется [Helm chart](https://projects.ronasit.com/k8s-tools/charts/laravel), для деплоя приложения нужно:

1) Создать репозиторий, куда необходимо скопировать laravel-empty-project
2) Установить переменные в Settings-CI/CD-Secure Variables:
   ```
   K8S_SECRET_APP_KEY
   K8S_SECRET_JWT_SECRET
   K8S_SECRET_OTHER_VARIABLE
   ```
   Соответсвующие переменные будут проброшены в контейнер.
3) Установить в .gitlab-ci.yml переменные:
   ```
   CI_PROJECT_NAME=`название проекта`
   DOMAIN=`домен для приложения`
   ```

После загрузки проекта в development ветку, автоматически начнется деплой, и приложение будет доступно по адресу https://dev.$DOMAIN

Подробнее по конфигурации деплоя можно посмотреть в документации [Helm chart](https://projects.ronasit.com/k8s-tools/charts/laravel)
