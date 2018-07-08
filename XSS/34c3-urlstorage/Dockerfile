FROM ubuntu:17.10

RUN apt-get -y update


RUN bash -c "debconf-set-selections <<< 'mysql-server mysql-server/root_password password FUCKmyL1f3AZiwqeci'"
RUN bash -c "debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password FUCKmyL1f3AZiwqeci'"

RUN apt-get -y install curl python3 python3-pip mysql-server libmysqlclient-dev nginx
RUN apt-get -y install wget
ENV DOCKER 1

# install phantomjs
RUN apt-get -y install bzip2 libfreetype6 libfontconfig
ENV PHANTOMJS_VERSION 2.1.1
RUN mkdir -p /srv/var && \
    wget --local-encoding=UTF-8 --no-check-certificate -O /tmp/phantomjs-$PHANTOMJS_VERSION-linux-x86_64.tar.bz2 https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-$PHANTOMJS_VERSION-linux-x86_64.tar.bz2 && \
    tar -xjf /tmp/phantomjs-$PHANTOMJS_VERSION-linux-x86_64.tar.bz2 -C /tmp && \
    rm -f /tmp/phantomjs-$PHANTOMJS_VERSION-linux-x86_64.tar.bz2 && \
    mv /tmp/phantomjs-$PHANTOMJS_VERSION-linux-x86_64/ /srv/var/phantomjs && \
    ln -s /srv/var/phantomjs/bin/phantomjs /usr/bin/phantomjs

RUN pip3 install django gunicorn mysqlclient requests lxml pyyaml django-simple-captcha

ADD mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

ADD dump.sql /tmp/dump.sql

RUN service mysql start && \
    mysql -uroot -pFUCKmyL1f3AZiwqeci < /tmp/dump.sql && \
    rm /tmp/dump.sql

COPY app /app
COPY nginx/default /etc/nginx/sites-available/default

RUN service mysql start && \
    cd /app && \
    python3 manage.py migrate && \
    python3 manage.py loaddata admin

# xss user
RUN groupadd -g 1000 xss-man && useradd -g xss-man -u 1000 xss-man 

# challenge files and configs
COPY scripts/ /xss/
RUN chown -R xss-man:xss-man /xss/ && chmod 500 /xss/* 

CMD service mysql start && \
    service nginx start && \
    cd /app && \
    (gunicorn urlstorage.wsgi --bind 127.0.0.1:8000 & \
    /xss/run_bot.sh & \
    /bin/bash -i)

