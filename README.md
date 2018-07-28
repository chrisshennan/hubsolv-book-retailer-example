# Notes
- This project comes complete with its own environment to ensure consistency across different machines
- API Responses are build around the JSON API specification found at http://jsonapi.org
# Setting up

1. Checkout the project

```
git clone git@github.com:chrisshennan/hubsolv-book-retailer-example.git
cd hubsolv-book-retailer-example
docker run -it --rm -v ${PWD}:/app -w /app composer install
```

2. Spin up the development environment (nginx, PHP, MySQL)

```
cd docker;
docker-compose up -d
```

3. Create the dev and test databases

```
# Dev
docker exec -it docker_php-hubsolv_1 /app/bin/console doctrine:database:create
docker exec -it docker_php-hubsolv_1 /app/bin/console doctrine:migrations:migrate
docker exec -it docker_php-hubsolv_1 /app/bin/console doctrine:fixtures:load

# Test
docker exec -it docker_php-hubsolv_1 /app/bin/console doctrine:database:create --env=test
docker exec -it docker_php-hubsolv_1 /app/bin/console doctrine:migrations:migrate --env=test
```

# Running tests (from root of the project)

Running with xdebug enabled for mac

```
docker run -it --rm --network=docker_hubsolv -e XDEBUG_CONFIG="remote_host=docker.for.mac.localhost remote_enable=1" -w ${PWD} -v ${PWD}:${PWD} thedrum/php:7-fpm-dev vendor/bin/behat
```

# Future Development
 - Pagination the responses 
 - Return bad request status code where invalid filter options are passed
 - ApiController::bookCreate to be refactored to use Symfony Forms
 - Add the links section to the API responses