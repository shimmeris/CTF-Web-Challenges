FROM andreisamuilik/php5.5.9-apache2.4-mysql5.5
ENV DEBIAN_FRONTEND noninteractive 

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
RUN apt-get update -y \ 
    && service mysql start \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* 

 
COPY ./src/index.php /app/index.php
COPY ./src/config.php /app/config.php

RUN chown -R www-data:www-data /app
COPY ./start.sh /tmp/start.sh
RUN chmod a+x /tmp/start.sh

RUN set -x \
    && service mysql start \ 
    && mysql  -e "CREATE DATABASE babytrick;"  -uroot  -proot \ 
    &&  mysql -e "grant select,create,insert on babytrick.* to 'babytrick'@'localhost' identified by 'babytrick1234' "  -uroot -proot

EXPOSE 80 3306
CMD ["/tmp/start.sh"]
