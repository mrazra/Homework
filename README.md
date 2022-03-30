# Exchange rates

A [Docker](https://www.docker.com/) based installer and runtime for the [Symfony](https://symfony.com) web framework, based on [this](https://github.com/dunglas/symfony-docker) repository from one of the Symfony core team members.

## Getting Started
1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. When running for the first time use build command `docker-compose up -d --build`, next time
   run `docker-compose up -d`
3. To create the database table, run `docker-compose exec php bin/console make:migration` and then `docker-compose exec php bin/console doctrine:migrations:migrate`
4. To install yarn, run `docker-compose exec php yarn install`
5. Run `docker-compose exec php yarn watch`
6. Open `http://localhost` in your favorite web browser

## Running the console app
1. For running the console app to populate the database use `docker-compose exec php bin/console app:update-exchange-rate`
2. If you wish to add additional data for an individual date, use `docker-compose exec php bin/console app:exchange-rates 2022-03-01` with the date in Y-m-d format

## Database
You can see the database in `http://localhost:8080`

## Shutdown
When you wish to stop and remove containers run `docker-compose down`
