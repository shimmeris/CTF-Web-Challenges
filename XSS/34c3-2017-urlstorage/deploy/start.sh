#!/bin/sh

service nginx restart

chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
service mysql restart

cd /app && \
python3 manage.py migrate && \
python3 manage.py loaddata admin

cd /app && \
nohup gunicorn urlstorage.wsgi --bind 127.0.0.1:8000& 

/xss/run_bot.sh & /bin/bash -i

/usr/bin/tail -f /dev/null