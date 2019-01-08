#!/bin/bash
chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
service mysql restart

service cron start
service apache2 start

echo 'INSERT INTO flag.flag VALUES("34C3_you_Extr4cted_the_unExtract0ble_plUs_you_knoW_s0me_SSRF");' | mysql -u root -pFUCKmyL1f3AZiwqecq

/usr/bin/tail -f /dev/null