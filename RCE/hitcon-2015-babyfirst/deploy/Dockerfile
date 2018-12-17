FROM ubuntu:16.04

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y

# 安装题目或许需要的辅助工具
RUN apt-get install -y wget curl

# 安装 PHP 及 nginx
RUN apt-get install -y nginx \
    php7.0-fpm

# 安装 crontab，每天 4 点清空 sandbox
RUN apt-get install -y cron
RUN echo '0 4 * * * root rm -rf /sandbox/*' >> /etc/crontab
 
# 文件移动
COPY ./default /etc/nginx/sites-available/default
COPY ./src/index.php /var/www/html/index.php
COPY ./start.sh /start.sh
RUN rm /var/www/html/*.html
RUN chmod a+x /start.sh

# 题目环境
RUN echo 'hitcon{b4by_f1rst}' > /flag
RUN ln -s /bin/true /bin/orange
RUN chown -R www-data:www-data /var/www/html \
    && ln -s /var/www/html /html
RUN mkdir /sandbox
RUN chown -R www-data /sandbox
RUN chmod -R 775 /sandbox

# 清除
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 
CMD ["/start.sh"]