#!/bin/bash

set -e  # Stop script on error
set -x  # Debug mode (prints all commands)

echo "🚀 Pulling latest Docker image..."
docker pull $DOCKER_USERNAME/user-service:latest

echo "🛑 Stopping old containers..."
docker-compose down || echo "No existing containers found"

echo "🔧 Removing unused images..."
docker image prune -f

echo "✅ Starting new containers..."
docker-compose up --build -d

echo "⏳ Waiting for MySQL to be ready..."
for i in {1..30}; do
  docker exec user-service-db mysqladmin ping -h 127.0.0.1 --silent && break
  echo "Waiting for MySQL..."
  sleep 2
done

echo "📦 Running database migrations..."
docker exec user-service-app php artisan migrate --force

echo "🧹 Clearing cache..."
docker exec user-service-app php artisan optimize:clear

echo "🎉 Deployment Complete!"
