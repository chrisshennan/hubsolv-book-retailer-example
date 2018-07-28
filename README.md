# Setting up

1. Spin up the development environment (nginx, PHP, MySQL)

```
cd docker;
docker-compose up -d
```

2. Create the dev and test databases

```
# Dev
docker exec -it docker_php-hubsolv_1 /app/bin/console doctrine:database:create


# Test
docker exec -it docker_php-hubsolv_1 /app/bin/console doctrine:database:create --env=test
```

# Running tests

```
docker run -it --rm --network=docker_hubsolv -e XDEBUG_CONFIG="remote_host=docker.for.mac.localhost remote_enable=1" -w ${PWD} -v ${PWD}:${PWD} thedrum/php:7-fpm-dev vendor/bin/behat
```