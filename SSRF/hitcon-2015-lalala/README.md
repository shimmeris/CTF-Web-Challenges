# 来源
[orangetw/My-CTF-Web-Challenges/hitcon-ctf-2015/lalala](https://github.com/orangetw/My-CTF-Web-Challenges/tree/master/hitcon-ctf-2015/lalala)
# 部署
docker build -t="ctf/lalala" .

docker run -d -p 8003:80 ctf/lalala