#!/usr/bin/env sh
set -e

# Skip recursive chown on bind mounts to avoid Windows/WSL permission issues

# Create storage symlink if it doesn't exist
if [ ! -L "/var/www/html/public/storage" ] && [ ! -d "/var/www/html/public/storage" ]; then
  echo "[entrypoint] Creating storage symlink..."
  php artisan storage:link || true
fi

# Install dependencies if vendor is missing
if [ ! -d "/var/www/html/vendor" ] || [ -z "$(ls -A /var/www/html/vendor 2>/dev/null)" ]; then
  echo "[entrypoint] Installing composer dependencies..."
  composer install --no-interaction --prefer-dist --no-progress
fi

# Generate app key if missing
if [ ! -f "/var/www/html/.env" ] && [ -f "/var/www/html/.env.example" ]; then
  cp /var/www/html/.env.example /var/www/html/.env
fi

if ! grep -q "^APP_KEY=\w" /var/www/html/.env 2>/dev/null; then
  php artisan key:generate --ansi || true
fi

# If using MySQL, wait for it to be available
if [ "${DB_CONNECTION}" = "mysql" ] || grep -q "^DB_CONNECTION=mysql" /var/www/html/.env 2>/dev/null; then
  DB_HOST_VAL="${DB_HOST:-mysql}"
  DB_PORT_VAL="${DB_PORT:-3306}"
  echo "[entrypoint] Waiting for MySQL at ${DB_HOST_VAL}:${DB_PORT_VAL}..."
  for i in $(seq 1 60); do
    if nc -z ${DB_HOST_VAL} ${DB_PORT_VAL}; then
      echo "[entrypoint] MySQL is up!"
      break
    fi
    sleep 1
  done
fi

# Run migrations (safe for local dev)
echo "[entrypoint] Running database migrations..."
php artisan migrate --force --no-interaction || {
  echo "[entrypoint] Migration failed, but continuing..."
  echo "[entrypoint] You may need to fix migration dependencies manually"
}

# Ensure Vite is not in hot mode; prefer built assets in Docker
if [ -f "/var/www/html/public/hot" ]; then
  echo "[entrypoint] Removing Vite hot file to use built assets..."
  rm -f /var/www/html/public/hot
fi

# Cache config/routes for performance in non-local env
if [ "${APP_ENV}" != "local" ]; then
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

exec "$@"
