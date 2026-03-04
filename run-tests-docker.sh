#!/usr/bin/env bash
set -euo pipefail

# Run FTIT integration tests inside the Docker web container.
# Uses TEST_BASE_URL=http://localhost:80 inside the container, which is
# exposed on host as http://localhost:8081.

cd "$(dirname "$0")"

echo "Building Docker image (if needed)..."
docker compose build web

echo "Starting web service (docker compose up -d --force-recreate web)..."
docker compose up -d --force-recreate web

echo "Running integration tests inside container..."
docker compose exec -e TEST_BASE_URL=http://localhost web php /var/www/tests/integration.php

