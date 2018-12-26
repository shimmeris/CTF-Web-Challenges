FROM ubuntu:16.04
ENV DEBIAN_FRONTEND noninteractive 

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get -y update

RUN apt-get -y install curl python3 python3-pip libmysqlclient-dev nginx
RUN apt-get -y install wget

# install phantomjs
RUN apt-get -y install bzip2 libfreetype6 libfontconfig
ENV PHANTOMJS_VERSION 2.1.1
RUN mkdir -p /srv/var && \
    wget --local-encoding=UTF-8 --no-check-certificate -O /tmp/phantomjs-$PHANTOMJS_VERSION-linux-x86_64.tar.bz2 https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-$PHANTOMJS_VERSION-linux-x86_64.tar.bz2 && \
    tar -xjf /tmp/phantomjs-$PHANTOMJS_VERSION-linux-x86_64.tar.bz2 -C /tmp && \
    rm -f /tmp/phantomjs-$PHANTOMJS_VERSION-linux-x86_64.tar.bz2 && \
    mv /tmp/phantomjs-$PHANTOMJS_VERSION-linux-x86_64/ /srv/var/phantomjs && \
    ln -s /srv/var/phantomjs/bin/phantomjs /usr/bin/phantomjs

# 文件移动
COPY db.sql /tmp/db.sql
COPY app /app
COPY nginx/default /etc/nginx/sites-available/default
COPY ./start.sh /start.sh

RUN chmod 777 /start.sh

# 数据库配置
RUN apt-get install -y mysql-client \
    mysql-server \
    && service mysql start \
    && mysqladmin -uroot password FUCKmyL1f3AZiwqeci \
    && mysql -e "source /tmp/db.sql;"  -uroot -pFUCKmyL1f3AZiwqeci   \
    && rm /tmp/db.sql

COPY mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

# 题目环境
RUN pip3 install --upgrade pip
RUN pip3 install django gunicorn mysqlclient requests lxml pyyaml django-simple-captcha


# xss user
RUN groupadd -g 1000 xss-man && useradd -g xss-man -u 1000 xss-man 

# challenge files and configs
COPY scripts/ /xss/
RUN chown -R xss-man:xss-man /xss/ && chmod 500 /xss/* 

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80
CMD ["/start.sh"]