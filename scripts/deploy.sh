#!/bin/bash
set -e

cd "$(dirname "$0")/.."

echo ">>> Pulling latest code..."
git pull origin main

echo ">>> Rebuilding containers (no cache)..."
docker compose -f compose.yaml -f compose.prod.yaml build --no-cache
docker compose -f compose.yaml -f compose.prod.yaml up -d

echo ">>> Waiting for container to be healthy..."
until docker compose exec -T laravel.test curl -sf http://127.0.0.1:80/up > /dev/null 2>&1; do
    sleep 2
done

echo ">>> Running migrations..."
docker compose exec -T laravel.test php artisan migrate --force

echo ">>> Clearing and re-caching config..."
docker compose exec -T laravel.test php artisan optimize:clear
docker compose exec -T laravel.test php artisan optimize

echo ""
echo ">>> Deploy complete!"
echo "    URL: https://inventory.shorai-lab.com"
echo "    Status: docker compose ps"
echo "    Logs:  docker compose logs -f laravel.test"