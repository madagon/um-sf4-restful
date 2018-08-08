#!/usr/bin/env bash
cd /www

setfacl -dR -m u:www-data:rwX -m u:root:rwX /www
setfacl -d -m u:www-data:rwX -m u:root:rwX /www
setfacl -R -m u:www-data:rwX -m u:root:rwX /www

php-fpm
