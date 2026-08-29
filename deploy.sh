#!/bin/bash

set -e

echo "=============================="
echo " ACTUALIZANDO VENTAS GR"
echo "=============================="

BEFORE=$(git rev-parse HEAD)

git pull --ff-only origin main

AFTER=$(git rev-parse HEAD)

if [ "$BEFORE" = "$AFTER" ]; then
    echo "No hay cambios nuevos."
    exit 0
fi

echo "Cambios descargados."

# Composer
if git diff --name-only "$BEFORE" "$AFTER" | grep -Eq 'composer\.json|composer\.lock'; then
    echo "Actualizando Composer..."

    docker compose exec -T app composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader
fi

# Frontend
if git diff --name-only "$BEFORE" "$AFTER" | grep -Eq 'package\.json|package-lock\.json|resources/(js|css)/'; then
    echo "Compilando frontend..."

    docker compose exec -T app npm ci
    docker compose exec -T app npm run build
fi

# Migraciones
if git diff --name-only "$BEFORE" "$AFTER" | grep -q '^database/migrations/'; then
    echo "Ejecutando migraciones..."

    docker compose exec -T app php artisan migrate --force
fi

# Docker
if git diff --name-only "$BEFORE" "$AFTER" | grep -Eq 'Dockerfile|docker-compose\.ya?ml|compose\.ya?ml'; then
    echo "Actualizando contenedores..."

    docker compose up -d --build --remove-orphans
fi

echo "Limpiando caché Laravel..."

docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan optimize

echo "=============================="
echo " ACTUALIZACIÓN COMPLETADA"
echo "=============================="