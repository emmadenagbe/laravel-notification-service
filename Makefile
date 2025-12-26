setup:
	@echo "Setting up environment..."
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@docker-compose build
	@docker-compose up -d
	@docker-compose exec app composer install
	@docker-compose exec app php artisan key:generate
	@docker-compose exec app php artisan migrate --force
	@echo "Setup complete! Access the app at http://localhost:4000"

up:
	docker-compose up -d

build:
	docker-compose build

down:
	docker-compose down

stop:
	docker-compose stop

restart:
	docker-compose restart

bash:
	docker-compose exec app bash

migrate:
	docker-compose exec app php artisan migrate

test:
	docker-compose exec app php artisan test

queue:
	docker-compose exec app php artisan queue:work rabbitmq --tries=3
