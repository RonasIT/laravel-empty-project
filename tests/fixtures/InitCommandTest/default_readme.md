# My App

Project description will be here

## Project Resources & Contacts
This section provides quick links to various resources and contacts associated
with this project. It's here to streamline your navigation and communication
process, so you can efficiently find what you need or reach out to who you need.

### Resources
Below are links to tools and services used in this project:
- Issue Tracker: Here, you can report any issues or bugs related to the project. [Issue Tracker](:issue_tracker_link)
- Figma: This is where we maintain all our design assets and mock-ups. [Figma](:figma_link)
- Sentry: To monitor application performance and error tracking. [Sentry](:sentry_link)
- DataDog: This is where we monitor our logs, and server performance, and receive alerts. [DataDog](:datadog_link)
- ArgoCD: Is a kubernetes controller which continuously monitors running applications. [ArgoCD](:argocd_link)
- Laravel Telescope: This is debug assistant for the Laravel framework. [Laravel Telescope](:telescope_link)
- [API Documentation](https://mysite.com)

### Contacts
Should you need assistance or have questions, feel free to connect with the following individuals:
- Manager: If you have any high-level project concerns, feel free to get in touch with our project manager. [Connect with Manager](:manager_link)
- Code Owner/Team Lead: For specific questions about the codebase or technical aspects, reach out to our team lead. [Connect with Team Lead](:team_lead_link)
Please be mindful of each individual's preferred contact method and office hours.

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
Build and start containers. It may takes some time.
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
Default admin email and password: `mail@mail.com`/`123456`