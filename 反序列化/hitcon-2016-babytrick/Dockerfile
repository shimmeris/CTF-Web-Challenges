FROM  ubuntu:16.04
ENV DEBIAN_FRONTEND noninteractive 

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y && \ 
    apt-get install -y nginx \
    php7.0-fpm \
    php-mysql  \ 
    mysql-client \
    mysql-server  \
    && /etc/init.d/mysql start \
    && mysqladmin -uroot password root  \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* 

 
COPY ./default /etc/nginx/sites-available/default
COPY ./index.php /usr/share/nginx/html/index.php
COPY ./config.php /usr/share/nginx/html/config.php

RUN chown -R www-data:www-data /usr/share/nginx/html \
    && ln -s /usr/share/nginx/html /html
COPY ./start.sh /tmp/start.sh
RUN chmod a+x /tmp/start.sh

RUN set -x \
    && service mysql start \ 
    && mysql  -e "CREATE DATABASE babytrick;"  -uroot  -proot \ 
    &&  mysql -e "grant select,create,insert on babytrick.* to 'babytrick'@'localhost' identified by 'babytrick1234' "  -uroot -proot

EXPOSE 80 3306
CMD ["/tmp/start.sh"]
