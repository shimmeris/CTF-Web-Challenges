FROM ubuntu:16.04 
ENV DEBIAN_FRONTEND noninteractive 

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
RUN TZ=Asia/shanghai 
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y && \ 
    apt-get install -y curl \
    inetutils-ping \
    nginx \
    php7.0-fpm \
    php-mysql  \ 
    mysql-client \
    mysql-server  \
    && /etc/init.d/mysql start \
    && mysqladmin -uroot password root  \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* 

# 拷贝文件 
COPY ./start.sh /tmp/
COPY ./tmp.sql /tmp/
RUN chmod a+x ./tmp/start.sh
# 设置数据库

RUN set -x \
    && service mysql start \ 
    && mysql  -e "CREATE DATABASE xdcms DEFAULT CHARACTER SET utf8;"  -uroot  -proot \ 
    &&  mysql -e "use xdcms;source /tmp/tmp.sql;"  -uroot -proot \
    && rm /tmp/tmp.sql 

# 复制源代码 
COPY ./default  /etc/nginx/sites-available/default
COPY ./src/    /usr/share/nginx/html/

# 修改目录权限
EXPOSE  80 3306
CMD ["/tmp/start.sh"]
