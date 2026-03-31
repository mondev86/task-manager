.PHONY: help build up down logs sh

help:
	@echo "Comandos disponibles:"
	@echo "  make build    - Construir contenedores"
	@echo "  make up       - Iniciar contenedores"
	@echo "  make down     - Detener contenedores"
	@echo "  make logs     - Ver logs"
	@echo "  make sh       - Entrar al contenedor app"

build:
	docker-compose build --no-cache

up:
	docker-compose up -d

down:
	docker-compose down

logs:
	docker-compose logs -f

sh:
	docker-compose exec app bash

migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed

fresh:
	docker-compose exec app php artisan migrate:fresh --seed
