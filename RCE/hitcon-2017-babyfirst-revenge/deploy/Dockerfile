FROM  ubuntu:16.04

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
RUN sed -i 's/security.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Update sources
RUN apt-get update -y

RUN apt-get install -y  bash-completion unzip build-essential gcc g++ autoconf libiconv-hook-dev wget

# nginx php
RUN apt-get install -y nginx php7.0-fpm  
RUN rm -rf /var/lib/apt/lists/*
 
COPY ./default /etc/nginx/sites-available/default
COPY ./src/index.php /usr/share/nginx/html/index.php
COPY ./src/flag.txt  /usr/share/nginx/flag

RUN  ln -s /bin/true /bin/orange
RUN chown -R www-data:www-data /usr/share/nginx/html \
    && ln -s /usr/share/nginx/html /html
COPY ./start.sh /start.sh
RUN chmod a+x /start.sh

EXPOSE 80 
CMD ["/start.sh"]