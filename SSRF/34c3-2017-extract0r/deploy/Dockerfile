FROM ubuntu:16.04

ENV DEBIAN_FRONTEND noninteractive
RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list


RUN apt-get -y update && apt-get -y install curl wget zip 

# apache & php & stuff
RUN apt-get -y install apache2 apt-transport-https php php-curl php-pclzip libapache2-mod-php p7zip-full cron

ENV WEBROOT /var/www/html
ENV MYSQL_USER=mysql
RUN rm /var/www/html/*.html

COPY db.sql /tmp/db.sql

RUN apt-get install -y mysql-client \
    mysql-server \
    php-mysql \
    && service mysql start \
    && mysqladmin -uroot password FUCKmyL1f3AZiwqecq \
    && mysql -e "source /tmp/db.sql;"  -uroot -pFUCKmyL1f3AZiwqecq   \
    && rm /tmp/db.sql

COPY mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf



# challenge files and configs
RUN (crontab -l ; echo "*/5 * * * * rm -r /var/www/html/files/* ; touch /var/www/html/files/index.php";\
        echo "*/5 * * * * rm -r /tmp/* && touch /tmp/index.php") | crontab -
COPY 000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY webroot/ /var/www/html/
RUN touch /tmp/index.php
RUN useradd extract0r -m
COPY files/create_a_backup_of_my_supersecret_flag.sh /home/extract0r/
RUN chown -R www-data /var/www/html/files && \
    chown extract0r:extract0r /home/extract0r/create_a_backup_of_my_supersecret_flag.sh

COPY ./start.sh /start.sh
RUN chmod 777 /start.sh



CMD ["/start.sh"]