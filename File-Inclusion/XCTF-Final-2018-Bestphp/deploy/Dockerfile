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

 
# 文件移动
COPY ./default /etc/nginx/sites-available/default
COPY ./src/* /var/www/html/

COPY ./start.sh /start.sh
RUN rm /var/www/html/*.html
RUN chmod a+x /start.sh

# 题目环境
RUN echo 'xctf{Funy_s3ss1On' > /flag
RUN chown -R www-data:www-data /var/www/html \
    && ln -s /var/www/html /html

# 清除
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 
CMD ["/start.sh"]