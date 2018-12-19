FROM ubuntu:16.04

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y

# 安装 PHP 及 nginx
RUN apt-get install -y nginx \
    php7.0-fpm

# 安装 SOAP 扩展
RUN apt-get install -y php-soap
 
# 文件移动
RUN rm /var/www/html/*.html
COPY ./default /etc/nginx/sites-available/default
COPY ./src/* /var/www/html/
COPY ./start.sh /start.sh
RUN chmod a+x /start.sh

# 题目环境
RUN chown -R www-data:www-data /var/www/html \
    && ln -s /var/www/html /html

# 清除
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 
CMD ["/start.sh"]