# My App

This project implements an API for the My App Mobile app.

## Project Resources & Contacts

This section provides quick links to various resources and contacts associated
with this project. It's here to streamline your navigation and communication
process, so you can efficiently find what you need or reach out to who you need.

### Resources

Below are links to tools and services used in this project:
- [Issue Tracker](https://gitlab.com/my-project): Here, you can report any issues or bugs related to the project.
- [Figma](https://figma.com/my-project): This is where we maintain all our design assets and mock-ups.
- [Sentry](https://sentry.com/my-project): To monitor application performance and error tracking.
- [DataDog](https://datadoghq.com/my-project): This is where we monitor our logs, and server performance, and receive alerts.
- [ArgoCD](https://argocd.com/my-project): Is a kubernetes controller which continuously monitors running applications.
- [Laravel Telescope](https://mypsite.com/telescope-link): This is debug assistant for the Laravel framework.
- [Laravel Nova](https://mypsite.com/nova-link): This is admin panel for the Laravel framework.
- [API Documentation](https://mysite.com)

### Contacts

Should you need assistance or have questions, feel free to connect with the following individuals:
- Manager: If you have any high-level project concerns, feel free to get in touch with our project manager. [Connect with Manager](mailto:manager@mail.com)
- Code Owner/Team Lead: For specific questions about the codebase or technical aspects, reach out to our team lead. [Connect with Team Lead](mailto:lead@mail.com)

Please be mindful of each individual's preferred contact method and office hours.

## Prerequisites

To work with this repository, you will need to have the following
installed:

- [Docker](https://www.docker.com)

## Getting Started

To get started with this repository, follow these steps:

Clone this repository to your local machine:

```sh
git clone https://github.com/ronasit/laravel-helpers.git
```

Open project directory:

```sh
cd laravel-helpers
```

Build and start containers, it may takes some time:

```sh
docker compose up -d
```

## Environments

This repository by default supports three environments: `local`, `development`,
and `testing`. Each environment is represented by an appropriate environment file:

| Environment | File | URL                                  |
| --- | --- |--------------------------------------|
| local | .env | [http://localhost](http://localhost) |
| testing | .env.testing | -                                    |
| development | .env.development | [https://mysite.com](https://mysite.com)               |

## Credentials and Access

Default admin access:
- email `mail@mail.com`
- password `123456`

Laravel Telescope access:
- email `mail@mail.com`
- password `123456`

Laravel Nova access:
- email `nova_mail@mail.com`
- password `654321`
