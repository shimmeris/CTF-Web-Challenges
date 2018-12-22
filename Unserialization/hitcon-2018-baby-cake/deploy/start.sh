#!/bin/bash
service nginx restart
service php5.6-fpm start

/usr/bin/tail -f /dev/null
