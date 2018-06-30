# 来源
[wonderkun/CTF_web/web200-2](https://github.com/wonderkun/CTF_web/blob/master/web200-2)

# 部署
docker build -t="ctf/web200" .
docker run -d -p 8001:80 ctf/web200