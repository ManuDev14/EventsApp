#!/bin/bash
# .github/scripts/rollback.sh

set -e

echo "Starting rollback process..."

APP_DIR="/home/emanuelvaca.com/public_html"
BACKUP_DIR="/home/emanuelvaca.com/backups"
SERVER="emanuelvaca.com@ssh.gb.stackcp.com"

# Get the latest backup
LATEST_BACKUP=$(ssh $SERVER "ls -t $BACKUP_DIR/backup_*.tar.gz | head -1")

if [ -z "$LATEST_BACKUP" ]; then
  echo "No backup found for rollback"
  exit 1
fi

echo "Rolling back to: $LATEST_BACKUP"

ssh $SERVER << EOF
cd $APP_DIR

echo "Enabling maintenance mode..."
php artisan down --retry=60

echo "Restoring from backup..."
tar -xzf $LATEST_BACKUP

echo "Running post-rollback commands..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Disabling maintenance mode..."
php artisan up

echo "Rollback completed successfully!"
EOF

echo "Rollback finished!"