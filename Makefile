-include .env
export

COMPOSE := docker compose

# Dublura intentionata peste LANG-ul setat in compose: incarcarea datelor e
# singurul loc unde o conexiune pe latin1 corupe ireversibil diacriticele
MYSQL_FLAGS := --default-character-set=utf8mb4

.DEFAULT_GOAL := help
.PHONY: help setup up down restart logs sh mysql db db-schema db-seed

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
	$(COMPOSE) exec -e MYSQL_PWD=$(DB_PASS) db mysql $(MYSQL_FLAGS) -u$(DB_USER) $(DB_NAME)

db-schema: ## Reincarca structura bazei de date
	$(COMPOSE) exec -T -e MYSQL_PWD=$(DB_PASS) db mysql $(MYSQL_FLAGS) -u$(DB_USER) $(DB_NAME) < db/schema.sql

db-seed: ## Reincarca datele de test
	$(COMPOSE) exec -T -e MYSQL_PWD=$(DB_PASS) db mysql $(MYSQL_FLAGS) -u$(DB_USER) $(DB_NAME) < db/seed.sql

db: db-schema db-seed ## Reface baza de date de la zero
