# Laravel Empty Project

[![Coverage Status](https://coveralls.io/repos/github/RonasIT/laravel-empty-project/badge.svg?branch=development)](https://coveralls.io/github/RonasIT/laravel-empty-project?branch=development)

This repository can be used to scaffold a Laravel project.

## Prerequisites

To work with this repository, you will need to have the following
installed:

- [Docker](https://www.docker.com)

## Getting Started

To get started with this repository, follow these steps:

Clone this repository to your local machine.
```sh
git clone git@github.com:RonasIT/laravel-empty-project.git
```
Remove the existing GitHub [remote](https://git-scm.com/docs/git-remote).
```sh
git remote remove origin
```
Add your project remote.
```sh
git remote add origin <project_git_url>
```
Build and start containers. It may takes some time.
```sh
docker compose up -d
```
Check docker containers health status.
```sh
docker ps
```
You should see something like this.
```
CONTAINER ID   IMAGE                       COMMAND                  CREATED              STATUS              PORTS                                                NAMES
5ae2e24d63bb   ronasit/php-nginx-dev:8.1   "/entrypoint bash -c…"   About a minute ago   Up About a minute   0.0.0.0:80->80/tcp, 0.0.0.0:443->443/tcp, 9000/tcp   laravel-empty-project-nginx-1
ef37a992c53c   webdevops/php:8.1-alpine    "/entrypoint supervi…"   About a minute ago   Up About a minute   9000/tcp                                             laravel-empty-project-php-1
e02e9f746731   ronasit/postgres:12.5       "docker-entrypoint.s…"   About a minute ago   Up About a minute   0.0.0.0:5433->5432/tcp                               laravel-empty-project-pgsql_test-1
4e1fda859342   ronasit/postgres:12.5       "docker-entrypoint.s…"   About a minute ago   Up About a minute   0.0.0.0:5432->5432/tcp                               laravel-empty-project-pgsql-1
728c83486f92   redis:6.2.3                 "docker-entrypoint.s…"   About a minute ago   Up About a minute   0.0.0.0:6379->6379/tcp                               laravel-empty-project-redis-1
```
Connect to the `nginx` container.
```sh
docker exec -i -t laravel-empty-project-nginx-1 /bin/bash
```
Init your new project.
```sh
php artisan init <project_name>
```
Set required configs: `contact.email` in the `configs/auto-doc.php`.

Run tests to generate documentation
```sh
php vendor/bin/phpunit tests/
```

API documentation can be accessed by visiting `http://localhost` in your
web browser.

### Environments

This repository by default supports three environments: `local`, `development`,
and `testing`. Each environment is represented by an appropriate environment file:

- .env
- .env.development
- .env.testing

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.
