#!/bin/bash
service mysql restart  

chown www-data:www-data /app -R

## clean danger 
rm -rf /var/www/phpinfo
sed -i "s/;session.upload_progress.enabled = On/session.upload_progress.enabled = Off/g" /etc/php5/cli/php.ini
sed -i "s/;session.upload_progress.enabled = On/session.upload_progress.enabled = Off/g" /etc/php5/apache2/php.ini

cd /etc/php5/apache2/conf.d/
rm 20-xdebug.ini
rm 20-memcached.ini
rm 20-memcache.ini

source /etc/apache2/envvars
tail -F /var/log/apache2/* &
exec apache2 -D FOREGROUND

