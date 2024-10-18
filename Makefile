SHELL := /bin/bash

start:
	docker compose up -d
	symfony console doctrine:database:drop --force || true
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate -n
	symfony serve:start --no-tls -d

stop:
	symfony serve:stop

tests:
	symfony console doctrine:database:drop --force --env=test || true
	symfony console doctrine:database:create --env=test
	symfony console doctrine:migrations:migrate -n --env=test
#	symfony console doctrine:fixtures:load -n --env=test
	symfony php bin/phpunit $(MAKECMDGOALS)
.PHONY: tests