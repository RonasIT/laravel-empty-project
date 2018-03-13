# Docker configuration

docker-compose содержит конфигурацию для PHP 7.1 с mysql, postgres, redis

Лишние сервисы на конкретном проекте можно удалить из docker-compose.yml

Примеры коннекта для postgres/mysql:

```
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=pgdb
DB_USERNAME=pguser
DB_PASSWORD=""
```

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=mysqldb
DB_USERNAME=root
DB_PASSWORD=""
```
