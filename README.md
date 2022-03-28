# Exchange rates

A [Docker](https://www.docker.com/) based installer and runtime for the [Symfony](https://symfony.com) web framework.  
Based on [this](https://github.com/dunglas/symfony-docker) repository from one of the Symfony core team members.

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. When running for the first time use build command `docker-compose up -d --build`, next time
   run `docker-compose up -d`
3. To create the database table, run `docker-compose exec php bin/console make:migration` and then `docker-compose exec php bin/console doctrine:migrations:migrate`
4. Open `http://localhost` in your favorite web browser
5. When you wish to stop and remove containers, networks, volumes, and images created by `up`, use `docker-compose down`


## Running the console app

For running the console app to populate the database use `docker-compose exec php bin/console app:update-exchange-rate`

## Database
You can see the database in `http://localhost:8080`
