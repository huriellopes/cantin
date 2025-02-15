#!/bin/sh

usage() {
    echo "Available commands: {init_sail|up|generate_key|clearcache|dev|build|migrate|seed|rollback|stop|restart|containerprod}"
    exit 1
}

if [ "$#" -lt 1 ]; then
    usage
fi

action=$1

case $action in
    init_sail)
        if [ ! -f .env ]; then
            echo "No .env file found, copying .env.example to .env..."
            cp .env.example .env
        fi

        echo "Installing the sail command...."
        docker run --rm \
            -u "$(id -u):$(id -g)" \
            -v "$(pwd):/var/www/html" \
            -w /var/www/html \
            laravelsail/php84-composer:latest \
            composer install --ignore-platform-reqs
        ;;
    up)
        echo "Starting repository containers...."
        ./vendor/bin/sail up -d
        ;;
    generate_key)
        echo "Running generate key...."
        ./vendor/bin/sail art key:generate
        ;;
    dev)
        echo "Starting the frontend server...."
        ./vendor/bin/sail npm install
        ./vendor/bin/sail npm run dev
        ;;
    build)
        echo "Building the frontend...."
        ./vendor/bin/sail npm build
        ;;
    clearcache)
        echo "Clearing all cache...."
        ./vendor/bin/sail composer dumpautoload
        ./vendor/bin/sail art optimize:clear
        ./vendor/bin/sail art view:clear
        ./vendor/bin/sail art cache:clear
        ./vendor/bin/sail art route:clear
        ./vendor/bin/sail art config:clear
        ;;
    migrate)
        echo "Running migrations...."
        ./vendor/bin/sail art migrate
        ;;
    seed)
        echo "Running seeders...."
        ./vendor/bin/sail art migrate:fresh --seed
        ;;
    rollback)
        echo "Deleting the tables...."
        ./vendor/bin/sail art migrate:rollback
        ;;
    stop)
        echo "Stopping containers...."
        ./vendor/bin/sail stop
        ;;
    restart)
        echo "Restarting containers...."
        ./vendor/bin/sail restart
        ;;
    containerprod)
        if [ ! -f .env ]; then
            echo "No .env file found, copying .env.example to .env..."
            cp .env.example .env
        fi

        echo "Starting repository containers production..."
        docker compose -f "docker-compose.prod.yml" up -d
        docker exec app composer install --optimize-autoload --no-dev
        docker exec app php artisan key:generate
        ;;
    migrateprod)
        echo "Running migrations in production..."
        docker exec app php artisan migrate
        ;;
    cacheprod)
        echo "Caching application in production..."
        docker exec app php artisan optimize
        docker exec app php artisan view:cache
        docker exec app php artisan route:cache
        docker exec app php artisan config:cache
        ;;
    *)
        usage
esac
