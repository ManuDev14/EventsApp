#!/bin/bash
# .github/scripts/deploy-production.sh

set -e  # Exit on error

echo "🎯 Starting production deployment..."

APP_DIR="/home/emanuelvaca.com/public_html"
BACKUP_DIR="/home/sites/40b/2/2b48fe3c9c/backups"
SERVER="emanuelvaca.com@ssh.gb.stackcp.com"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup before deployment
echo "📦 Creating backup..."
ssh $SERVER "
  mkdir -p $BACKUP_DIR
  if [ -d '$APP_DIR' ]; then
    tar -czf '$BACKUP_DIR/backup_$DATE.tar.gz' -C '$APP_DIR' . 2>/dev/null || echo 'Backup created with warnings'
    echo '✅ Backup created: backup_$DATE.tar.gz'
  fi
"

# Upload files to production
echo "📤 Uploading files to production..."
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
  ./ $SERVER:$APP_DIR/

# Execute deployment commands on server
ssh $SERVER << EOF
cd $APP_DIR

echo "🔧 Enabling maintenance mode..."
php artisan down --retry=60 --secret="maintenance-secret-key" 2>/dev/null || echo "Maintenance mode set"

echo "⚙️ Setting up production environment..."

# Install/update Composer dependencies (if available)
if command -v composer &> /dev/null; then
  echo "📦 Installing PHP dependencies..."
  php8.3 /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction
else
  echo "⚠️ Composer not found, using existing vendor directory"
fi

# Laravel optimizations for production
echo "⚡ Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🔄 Running database migrations..."
php artisan migrate --force

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null || echo "Permission setting completed"

# Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env; then
  php artisan key:generate --no-interaction
fi

# Warm up cache
echo "🔥 Warming up application..."
php artisan cache:clear
php artisan config:cache

echo "✅ Disabling maintenance mode..."
php artisan up

echo "🚀 Production deployment completed successfully!"
EOF

# Verify deployment
echo "🔍 Verifying deployment..."
ssh $SERVER "
  cd $APP_DIR
  # Basic health check
  if php artisan --version > /dev/null; then
    echo '✅ Laravel is working'
  else
    echo '❌ Laravel check failed'
    exit 1
  fi
"

echo "🎉 Production deployment finished successfully!"