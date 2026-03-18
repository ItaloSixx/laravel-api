
DC        = docker compose
APP       = $(DC) exec app
ARTISAN   = $(APP) php artisan

.PHONY: help build up down restart logs shell \
        install migrate seed fresh key test \
        import-hotels import-rooms import-rates import-reservations import-all

## ── Ajuda ────────────────────────────────────────────────────────────────────
help: ## Exibe esta ajuda
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
	  awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-25s\033[0m %s\n", $$1, $$2}'

## ── Docker ───────────────────────────────────────────────────────────────────
build: ## Build das imagens Docker
	$(DC) build --no-cache

up: ## Sobe os containers em background
	$(DC) up -d

down: ## Derruba os containers
	$(DC) down

restart: ## Reinicia os containers
	$(DC) restart

logs: ## Exibe logs em tempo real
	$(DC) logs -f

shell: ## Abre shell no container app
	$(APP) bash

## ── Laravel Setup ────────────────────────────────────────────────────────────
install: ## Instala dependências Composer
	$(APP) composer install

key: ## Gera APP_KEY
	$(ARTISAN) key:generate

migrate: ## Executa as migrations
	$(ARTISAN) migrate --force

fresh: ## Apaga e recria o banco com migrations
	$(ARTISAN) migrate:fresh --force

seed: ## Executa os seeders
	$(ARTISAN) db:seed --force

## ── Importações XML ──────────────────────────────────────────────────────────
import-hotels: ## Importa hotels.xml
	curl -s -X POST http://localhost:$${APP_PORT:-8000}/api/v1/import/hotels | cat

import-rooms: ## Importa rooms.xml
	curl -s -X POST http://localhost:$${APP_PORT:-8000}/api/v1/import/rooms | cat

import-rates: ## Importa rates.xml
	curl -s -X POST http://localhost:$${APP_PORT:-8000}/api/v1/import/rates | cat

import-reservations: ## Importa reservations.xml
	curl -s -X POST http://localhost:$${APP_PORT:-8000}/api/v1/import/reservations | cat

import-all: import-hotels import-rooms import-rates import-reservations ## Importa todos os XMLs em ordem
	@echo "✅ Todos os XMLs importados!"

## ── Testes ───────────────────────────────────────────────────────────────────
test: ## Roda todos os testes (SQLite :memory:)
	$(APP) php artisan test --env=testing

test-unit: ## Roda apenas testes unitários
	$(APP) php artisan test --testsuite=Unit

test-feature: ## Roda apenas testes de feature
	$(APP) php artisan test --testsuite=Feature

## ── Setup inicial completo ───────────────────────────────────────────────────
setup: build up key migrate ## Build + up + key:generate + migrate
	@echo "🚀 Ambiente Docker pronto em http://localhost:$$(grep APP_PORT .env | cut -d= -f2)"
