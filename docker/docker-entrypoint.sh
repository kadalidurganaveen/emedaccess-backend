#!/bin/bash
set -e

# Ensure permissions for storage
mkdir -p /var/www/storage/framework/sessions /var/www/storage/framework/views /var/www/storage/framework/cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true
chmod -R 755 /var/www/storage || true

# If artisan exists, clear cached config so runtime env vars are used
if [ -f /var/www/artisan ]; then
  # Only force-generate key if APP_KEY is empty to avoid overriding a set key
  if [ -z "${APP_KEY:-}" ]; then
    php /var/www/artisan key:generate --force || true
  fi

  # Ensure configuration is cleared at runtime so Render's env vars are respected
  php /var/www/artisan config:clear || true
  php /var/www/artisan route:clear || true
  php /var/www/artisan view:clear || true
fi

# Execute the container command (Apache)
exec "$@"
