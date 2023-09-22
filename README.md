# Laravel Empty Project

[![Coverage Status](https://coveralls.io/repos/github/RonasIT/laravel-empty-project/badge.svg?branch=development)](https://coveralls.io/github/RonasIT/laravel-empty-project?branch=development)

This monorepo can be used to scaffold a Laravel project.

## Prerequisites

To work with this repository, you will need to have the following
installed:

- [Docker](https://www.docker.com)

## Getting Started

To get started with this monorepo, follow these steps:

1. Clone this repository to your local machine.
2. Run `git remote remove origin` to remove the existing
   [remote](https://git-scm.com/docs/git-remote).
3. Run `git remote add origin <your_remove_url>` to add your
   new remote.
4. Run `docker compose up -d` to build and start containers.
   It may takes some time.
5. Run `docker ps` to check that all five containers were started.
6. Connect with the `nginx` container by running
   `docker exec -i -t laravel-empty-project-nginx-1 /bin/bash`.
7. Run `php artisan init <project_name>` to initialize your new project.
8. Set `contact.email` value in the `configs/auto-doc.php`.
9. Run `php vendor/bin/phpunit tests/` to check if everything
   is ok and build documentation.

API documentation can be accessed by visiting `http://localhost` in your
web browser.

### Environments

This monorepo by default supports three environments: _local_, _development_,
and _testing_. Each environment is represented by an appropriate environment file:

- .env
- .env.development
- .env.testing

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.
