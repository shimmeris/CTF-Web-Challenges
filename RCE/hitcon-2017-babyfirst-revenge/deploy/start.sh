#!/bin/bash
service nginx restart
service php7.0-fpm start
service cron start

chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
service mysql restart  

/usr/bin/tail -f /dev/null