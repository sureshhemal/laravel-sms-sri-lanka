#!/bin/bash

# Manual Packagist Update Script
# Usage: ./update-packagist.sh [your-packagist-token]

if [ -z "$1" ]; then
    echo "Usage: $0 <packagist-token>"
    echo "Get your token from: https://packagist.org/profile/"
    exit 1
fi

TOKEN="$1"
PACKAGE_URL="https://github.com/sureshhemal/laravel-sms-sri-lanka"

echo "Updating Packagist for package: sureshhemal/laravel-sms-sri-lanka"

curl -X POST \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"repository\":{\"url\":\"$PACKAGE_URL\"}}" \
  https://packagist.org/api/update-package

echo ""
echo "Update request sent to Packagist!"
echo "Check https://packagist.org/packages/sureshhemal/laravel-sms-sri-lanka for updates"
