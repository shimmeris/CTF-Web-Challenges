FROM ubuntu:16.04
ENV DEBIAN_FRONTEND noninteractive 

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y

# 安装题目或许需要的辅助工具
RUN apt-get install -y curl wget

# 安装 PHP 及 nginx
RUN apt-get install -y nginx \
    php7.0-fpm

# 安装 crontab，每天 4 点清空 sandbox
RUN apt-get install -y cron
RUN echo '0 4 * * * root rm -rf /www/sandbox/*' >> /etc/crontab

# 文件移动
COPY ./default /etc/nginx/sites-available/default
COPY ./src/index.php /var/www/html/index.php
COPY ./db.sql /tmp/db.sql
COPY ./start.sh /start.sh

RUN rm /var/www/html/*.html

RUN chown -R www-data:www-data /var/www/html \
    && ln -s /var/www/html /html
RUN chmod a+x /start.sh

# 题目环境
RUN mkdir /www
RUN mkdir /www/sandbox
RUN chown -R www-data /www/sandbox
RUN chmod -R 775 /www/sandbox

RUN echo 'Flag is in the MySQL database\nfl4444g / SugZXUtgeJ52_Bvr' > /README.txt

# 数据库配置
RUN apt-get install -y php-mysql \
    mysql-client \
    mysql-server \
    && service mysql start \
    && mysqladmin -uroot password HuQ3stwHJ \
    && mysql -e "source /tmp/db.sql;"  -uroot -pHuQ3stwHJ \
    && rm /tmp/db.sql

# 清除
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 
CMD ["/start.sh"]