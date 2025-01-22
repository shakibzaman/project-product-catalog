#!/bin/sh

# Start Supervisor
supervisord -c /etc/supervisor/conf.d/supervisord.conf

# Wait for Supervisor to fully start
sleep 3

# Start PHP-FPM
php-fpm