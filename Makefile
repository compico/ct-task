docker_exec = docker-compose exec php
docker_php_exec = docker-compose exec php php
docker_artisan_exec = docker-compose exec php php artisan

dclear:
	$(docker_exec) make clean

clean:
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
	php artisan event:clear
	php artisan optimize:clear
	php artisan clear-compiled
	rm -rf bootstrap/cache/*.php
	rm -rf storage/framework/cache/*
	rm -rf storage/framework/views/*
	composer dump-autoload

dmigrate:
	$(docker_artisan_exec) migrate
