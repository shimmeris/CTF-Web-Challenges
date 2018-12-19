FROM andreisamuilik/php5.5.9-apache2.4-mysql5.5

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

RUN apt-get update

RUN a2enmod rewrite

# 环境源码移动
ADD nu1lctf.tar.gz /app/
COPY sql.sql /tmp/sql.sql
COPY run.sh /run.sh
RUN mkdir /home/nu1lctf
COPY clean_danger.sh /home/nu1lctf/clean_danger.sh
COPY clean_danger.sh /etc/cron.daily/clear_pic.sh
COPY clear_db.sh /etc/cron.daily/clear_db.sh

# 权限设置
RUN chmod +x /run.sh
RUN chmod 777 /tmp/sql.sql
RUN chmod 555 /home/nu1lctf/clean_danger.sh

EXPOSE 80
CMD ["/run.sh"]

