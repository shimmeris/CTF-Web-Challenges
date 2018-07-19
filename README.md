本项目只是对历届 CTF 开源的 Web 题源码进行了一个整理分类，并提供一个简单的搭建方法（毕竟目前公开的题目提供 Dockerfile 及 sql 文件的是少数。。）

# 申明
由于本人并未向出题人申请重新对题目进行修改发布的权利，但对每个 Web 题均标明了出处，并将所有用到题目的 repo 连接全部放在了下面，如有涉嫌侵权，实在是抱歉，还请麻烦告知，我会立即撤掉题目。

因为部分题目源码没有 flag ，因此这些题目中的 flag 为我自己随便添加的

对提供 Dockerfile及 SQL文件 的题目会做适当修改，对于大部分没有的题目，会自己~~编写~~ (复制粘贴)提供相应的文件

# 搭建
每道题目对应的端口需要自行在 build 脚本中更改，默认为 8000-8100
```shell
cd the_challenge
chmod 777 build run
./build
./run
```

# 分类

## SQLi
[web100-6](https://github.com/inory009/CTF-Web-Challenges/tree/master/SQLi/web100-6)

## RCE
[web200-2](https://github.com/inory009/CTF-Web-Challenges/tree/master/RCE/web200-2)

[hitcon-2015-babyfirst](https://github.com/inory009/CTF-Web-Challenges/tree/master/RCE/hitcon-2015-babyfirst)

[hitcon-2015-babyfirst-revenge](https://github.com/inory009/CTF-Web-Challenges/tree/master/RCE/hitcon-2017-babyfirst-revenge)

[hitcon-2015-babyfirst](https://github.com/inory009/CTF-Web-Challenges/tree/master/RCE/hitcon-2017-babyfirstv2-revenge-)

## SSRF
[hitcon-2015-lalala](https://github.com/inory009/CTF-Web-Challenges/tree/master/SSRF/hitcon-2015-lalala) （环境暂时有问题）

## XSS
[34c4-urlstorage](https://github.com/inory009/CTF-Web-Challenges/tree/master/XSS/34c3-urlstorage)

[rctf-2018-amp](https://github.com/inory009/CTF-Web-Challenges/tree/master/XSS/rctf-2018-amp)（环境暂时存在问题）

## 反序列化
[hitcon-2016-babytrick](https://github.com/inory009/CTF-Web-Challenges/tree/master/反序列化/hitcon-2016-babytrick)


## 文件包含
[LCTF-2017-萌萌哒的报名系统](https://github.com/inory009/CTF-Web-Challenges/tree/master/文件包含/LCTF-2017-萌萌哒的报名系统)

## 综合
[n1ctf-2018-easy_harder_php](https://github.com/inory009/CTF-Web-Challenges/tree/master/反序列化/n1ctf-2018-easy_harder_php)


# 来源
[l3m0n/My_CTF_Challenges](https://github.com/l3m0n/My_CTF_Challenges.git)

[orangetw/My-CTF-Web-Challenges](https://github.com/orangetw/My-CTF-Web-Challenges)

[l4wio/CTF-challenges-by-me](https://github.com/l4wio/CTF-challenges-by-me)

[otakekumi/CTF-Challenge](https://github.com/otakekumi/CTF-Challenge)

[eboda/34c3ctf](https://github.com/eboda/34c3ctf)

[firesunCN/My_CTF_Challenges](https://github.com/firesunCN/My_CTF_Challenges)

[munsiwoo/ctf-web-prob](https://github.com/munsiwoo/ctf-web-prob)

[google/google-ctf](https://github.com/google/google-ctf)

[sixstars/starctf2018](https://github.com/sixstars/starctf2018)

[Nu1LCTF/n1ctf-2018](https://github.com/Nu1LCTF/n1ctf-2018)

[wonderkun/CTF_web](https://github.com/wonderkun/CTF_web)

[zsxsoft/my-rctf-2018](https://github.com/zsxsoft/my-rctf-2018)

[vidar-team/HCTF2017](https://github.com/vidar-team/HCTF2017)

[vidar-team/HCTF2016](https://github.com/vidar-team/HCTF2016)

[LCTF/LCTF2017](https://github.com/LCTF/LCTF2017)

[grt1st/LCTF2017-WEB-LPLAYGROUND](https://github.com/grt1st/LCTF2017-WEB-LPLAYGROUND)