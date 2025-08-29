#!/bin/bash
# .github/scripts/deploy-staging.sh

set -e  # Exit on error

echo "🚀 Starting staging deployment..."

STAGING_DIR="/home/sites/40b/2/2b48fe3c9c/public_html/staging/eventapp"
SERVER="emanuelvaca.com@ssh.gb.stackcp.com"

# Create staging directory on server
ssh $SERVER "mkdir -p $STAGING_DIR"

# Upload files to staging
echo "📤 Uploading files to staging..."
rsync -avz --delete \
  --exclude='.git' \
  --exclude='.github' \
  --exclude='node_modules' \
  --exclude='tests' \
  --exclude='.env.example' \
  --exclude='README.md' \
  --exclude='package*.json' \
  --exclude='vite.config.js' \
  --exclude='tailwind.config.js' \
  -e "ssh" \
  ./ $SERVER:$STAGING_DIR/

# Execute deployment commands on server
ssh $SERVER << EOF
cd $STAGING_DIR

echo "⚙️ Setting up staging environment..."

# Create .env for staging if doesn't exist
if [ ! -f .env ]; then
  cp .env.example .env
  echo "📝 Please configure .env file for staging"
fi

# Install/update Composer dependencies 
php8.3 /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction

# Laravel optimizations
echo "⚡ Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🔄 Running migrations..."
php artisan migrate --force

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null || echo "Permission setting completed"

# Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env; then
  php artisan key:generate --no-interaction
fi

echo "✅ Staging deployment completed!"
EOF

echo "🎉 Staging deployment finished successfully!"