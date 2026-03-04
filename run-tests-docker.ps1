$ErrorActionPreference = "Stop"

# Run FTIT integration tests inside the Docker web container.
# Uses TEST_BASE_URL=http://localhost:80 inside the container, which is
# exposed on host as http://localhost:8081.

Set-Location $PSScriptRoot

Write-Host "Building Docker image (if needed)..." -ForegroundColor Cyan
docker compose build web

Write-Host "Starting web service (docker compose up -d --force-recreate web)..." -ForegroundColor Cyan
docker compose up -d --force-recreate web

Write-Host "Running integration tests inside container..." -ForegroundColor Cyan
docker compose exec -e TEST_BASE_URL=http://localhost web php /var/www/tests/integration.php

if ($LASTEXITCODE -ne 0) {
  Write-Host "Integration tests failed with exit code $LASTEXITCODE" -ForegroundColor Red
  exit $LASTEXITCODE
} else {
  Write-Host "Integration tests completed successfully." -ForegroundColor Green
}

