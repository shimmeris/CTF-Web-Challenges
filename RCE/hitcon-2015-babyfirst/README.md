# 来源
[orangetw/My-CTF-Web-Challenges/hitcon-ctf-2015/babyfirst/](https://github.com/orangetw/My-CTF-Web-Challenges/tree/master/hitcon-ctf-2015/babyfirst)
# 部署
docker build -t="ctf/babyfirst" .

docker run -d -p 8002:80 ctf/babyfirst