#!/bin/bash
set -e

cd "$(dirname "$0")/.."

echo ">>> Pulling latest code..."
git pull origin main

echo ">>> Rebuilding containers..."
docker compose -f compose.yaml -f compose.prod.yaml up -d --build

echo ">>> Running migrations..."
docker compose exec -T laravel.test php artisan migrate --force

echo ">>> Clearing and re-caching config..."
docker compose exec -T laravel.test php artisan optimize:clear
docker compose exec -T laravel.test php artisan config:cache

echo ">>> Restarting..."
docker compose restart laravel.test

echo ""
echo ">>> Deploy complete!"
echo "    URL: https://inventory.shorai-lab.com"
echo "    Status: docker compose ps"
echo "    Logs:  docker compose logs -f laravel.test"