#!/bin/bash

# dashboard-monitoring installer for Mac
# Author: Antigravity

set -e

echo "🚀 Starting installation for Dashboard Monitoring..."

# 1. Verification
echo "🔍 Checking requirements..."

if ! command -v php &> /dev/null; then
  echo "❌ PHP is not installed. Please install it with 'brew install php'."
  exit 1
fi

if ! command -v mysql &> /dev/null; then
  echo "⚠️  MySQL client not found. (If you use a remote DB, ignore this)."
fi

# 2. Database Config
echo ""
echo "📝 Database Configuration"
read -p "Database Host [127.0.0.1]: " DB_HOST
DB_HOST=${DB_HOST:-127.0.0.1}

read -p "Database Name [homelab_dashboard]: " DB_NAME
DB_NAME=${DB_NAME:-homelab_dashboard}

read -p "Database User [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -sp "Database Password []: " DB_PASS
echo ""
DB_PASS=${DB_PASS:-""}

# 3. Create db.php from template
echo "⚙️  Generating config/db.php..."
sed -e "s/{{DB_HOST}}/${DB_HOST}/g" \
    -e "s/{{DB_NAME}}/${DB_NAME}/g" \
    -e "s/{{DB_USER}}/${DB_USER}/g" \
    -e "s/{{DB_PASS}}/${DB_PASS}/g" \
    config/db.template.php > config/db.php

# 4. Database Setup (Optional)
read -p "📦 Import database schema (schema.sql)? [y/N]: " IMPORT_DB
if [[ $IMPORT_DB =~ ^[Yy]$ ]]; then
  echo "Importing schema..."
  mysql -h "$DB_HOST" -u "$DB_USER" "-p$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;" 2>/dev/null || true
  mysql -h "$DB_HOST" -u "$DB_USER" "-p$DB_PASS" "$DB_NAME" < sql/schema.sql
  echo "✅ Database imported successfully."
fi

# 5. Permission start script
chmod +x start.sh

# 6. Success message
echo ""
echo "✨ Installation complete! ✨"
echo "To start the dashboard, run: ./start.sh"
echo "Then visit: http://localhost:8000"
echo ""

# Optional: Add alias
read -p "💡 Do you want to add 'dashboard' alias to your .zshrc? [y/N]: " ADD_ALIAS
if [[ $ADD_ALIAS =~ ^[Yy]$ ]]; then
  echo "alias dashboard='cd $(pwd) && ./start.sh'" >> ~/.zshrc
  echo "✅ Alias added. Restart your terminal or run 'source ~/.zshrc'."
fi
