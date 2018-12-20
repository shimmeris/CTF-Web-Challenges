FROM ubuntu:16.04

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y

# 安装 PHP 及 nginx
RUN apt-get install -y apache2 \ 
    php7.0 \
    libapache2-mod-php7.0 \
    php7.0-cli

# 安装 crontab，每天 4 点清空 sandbox
RUN apt-get install -y cron
RUN echo '0 4 * * * root rm -rf /var/www/data/*' >> /etc/crontab
 
# 文件移动
COPY default /etc/apache2/sites-available/000-default.conf

COPY ./src/index.php /var/www/html/index.php
COPY ./src/read_flag /read_flag
COPY ./src/read_secret /read_secret
COPY ./start.sh /start.sh
RUN rm /var/www/html/*.html
RUN chmod a+x /start.sh
RUN a2enmod rewrite


# 题目环境
RUN chmod u+s /read_flag
RUN chown -R www-data:www-data /var/www/html \
    && ln -s /var/www/html /html

RUN mkdir /var/www/data
RUN chown www-data /var/www/data
RUN chmod -R 775 /var/www/data
RUN echo 'hitcon{Th3 d4rk fl4m3 PHP Mast3r}' > /flag
RUN chmod 700 /flag

# 清除
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 
CMD ["/start.sh"]