# Api Traefik 

## Requirements
- Docker
- Docker Compose

## Usage
- Clone this repository
- Run `docker compose up -d`
- Run `docker compose exec php bin/console doctrine:schema:create` to create the database.
- Run `docker compose exec php bin/console doctrine:fixtures:load` to load dummy data.

- Open http://traefik.localhost in your browser to access the Traefik dashboard

## Services
- Open http://frontend.localhost in your browser for NodeJS (the frontend)
- Open http://api.localhost in your browser for NginX (the backend/api)
