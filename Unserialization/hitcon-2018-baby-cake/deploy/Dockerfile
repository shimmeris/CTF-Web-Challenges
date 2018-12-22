FROM ubuntu:16.04

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# 清除 PHP 包
RUN apt-get purge `dpkg -l | grep php| awk '{print $2}' |tr "\n" " "`

RUN apt-get update -y

# 增加源下载 php5.6
RUN apt-get install -y software-properties-common
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update -y


# 安装 php5.6 及 nginx
RUN apt-get install -y nginx
RUN apt-get install -y php5.6 php5.6-fpm php5.6-intl php5.6-mbstring
 
# 文件移动
COPY ./default /etc/nginx/sites-available/default
COPY ./start.sh /start.sh

COPY ./src/read_flag /read_flag

COPY ./src/src.tar /var/www
RUN rm -r /var/www/html
RUN cd /var/www && tar xvf baby_cake.tgz && mv baby_cake.tgz ./html/webroot/
RUN chmod a+x /start.sh


# 题目环境
RUN chmod 777 /read_flag
RUN chown -R www-data:www-data /var/www/html \
    && ln -s /var/www/html /html

RUN echo 'hitcon{smart_implementation_of_CURLOPT_SAFE_UPLOAD><}' > /flag
RUN chmod 700 /flag

 
# 清除
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 
CMD ["/start.sh"]