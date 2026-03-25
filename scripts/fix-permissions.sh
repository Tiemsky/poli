#!/bin/bash

set -e  # Arrêter en cas d'erreur

echo "🔧 Laravel Sail - Fix des Permissions"
echo "======================================"

# Déterminer la commande Sail
if command -v sail &> /dev/null; then
    SAIL="sail"
    echo "✓ Commande 'sail' trouvée dans le PATH"
elif [ -f "./vendor/bin/sail" ]; then
    SAIL="./vendor/bin/sail"
    echo "✓ Utilisation de ./vendor/bin/sail"
else
    echo "❌ Erreur: Sail non trouvé!"
    echo "   Essayez: docker-compose exec laravel.test bash"
    exit 1
fi

echo ""
echo "📁 1. Fixing container permissions..."
$SAIL bash -c "
    chown -R www-www-data /var/www/html/storage && \
    chown -R www-www-data /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache && \
    touch /var/www/html/storage/logs/laravel.log && \
    chown www-www-data /var/www/html/storage/logs/laravel.log && \
    chmod 664 /var/www/html/storage/logs/laravel.log && \
    echo '✅ Container permissions OK'
"

echo ""
echo "📁 2. Fixing host permissions..."
sudo chown -R $USER:$USER storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
echo "✅ Host permissions OK"

echo ""
echo "🔄 3. Restarting services..."
$SAIL restart laravel.test

echo ""
echo "🗑️  4. Clearing caches..."
$SAIL php artisan optimize:clear

echo ""
echo "======================================"
echo "✨ Permissions fixées avec succès!"
echo ""
echo " Testez avec:"
echo "   $SAIL php artisan route:list"
echo ""
echo "🌐 Ou ouvrez: http://localhost"
