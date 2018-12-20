#!/bin/bash

#Configure the apache2
sed 's/Indexes //' /etc/apache2/apache2.conf > /etc/apache2/apache2.conf.new
sed 's/MaxConnectionsPerChild   0/MaxConnectionsPerChild   100/' /etc/apache2/mods-available/mpm_prefork.conf > /etc/apache2/mods-available/mpm_prefork.conf.new
mv /etc/apache2/apache2.conf.new /etc/apache2/apache2.conf
mv /etc/apache2/mods-available/mpm_prefork.conf.new /etc/apache2/mods-available/mpm_prefork.conf
echo '<Directory "/var/www/data">\n\tphp_flag engine off\n</Directory>' >> /etc/apache2/sites-enabled/000-default.conf


service cron start


/usr/bin/tail -f /dev/null