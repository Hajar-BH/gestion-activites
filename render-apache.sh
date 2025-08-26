#!/usr/bin/env bash
set -e
PORT="${PORT:-8080}"
sed -ri "s/Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf
sed -ri "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf
exec apache2-foreground
