# 来源
[orangetw/My-CTF-Web-Challenges/hitcon-ctf-2016/babytrick/](https://github.com/orangetw/My-CTF-Web-Challenges/tree/master/hitcon-ctf-2016/babytrick)
# 部署
docker build -t="ctf/babytrick" .

docker run -d -p 8005:80 ctf/babytrick

# 声明
由于 Dockerfile 中使用了 php7， 不支持原本源码源码中的 mysql 库，因此全部改写成了 mysqli 库。