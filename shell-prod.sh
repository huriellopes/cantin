#!/usr/bin/sh

# Limpa o console
echo "Limpando o console..."
clear

# Limpa o cachce
echo "Limpando o cache..."
php artisan config:clear &&
php artisan cache:clear &&
php artisan optimize:clear &&
php artisan view:clear &&
php artisan filament:optimize-clear

## Cacheia a aplicação
echo "Cacheando a aplicação..."
php artisan optimize &&
php artisan view:cache &&
php artisan config:cache &&
php artisan route:cache &&
php artisan event:cache &&
php artisan filament:optimize
