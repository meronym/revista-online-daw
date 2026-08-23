-include .env
export

COMPOSE := docker compose

.DEFAULT_GOAL := help
.PHONY: help setup up down restart logs sh mysql

help: ## Afiseaza comenzile disponibile
	@grep -E '^[a-z-]+:.*?## ' $(MAKEFILE_LIST) \
		| awk -F':.*?## ' '{printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2}'

setup: ## Creeaza .env din .env.example daca nu exista
	@test -f .env || (cp .env.example .env && echo "Am creat .env - completeaza parolele inainte de 'make up'")

up: setup ## Porneste stack-ul local
	$(COMPOSE) up -d --build

down: ## Opreste stack-ul
	$(COMPOSE) down

restart: ## Reporneste containerele
	$(COMPOSE) restart

logs: ## Urmareste log-urile
	$(COMPOSE) logs -f

sh: ## Deschide un shell in containerul PHP
	$(COMPOSE) exec php bash

mysql: ## Deschide clientul MySQL pe baza de date
	$(COMPOSE) exec db mysql -u$(DB_USER) -p$(DB_PASS) $(DB_NAME)
