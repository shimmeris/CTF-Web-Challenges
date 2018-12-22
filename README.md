本项目只是对历届 CTF 开源的 Web 题源码进行了一个整理分类，并提供一个简单的搭建方法（毕竟目前公开的题目提供 Dockerfile 及 sql 文件的是少数）

# 申明
由于本人并未向出题人申请重新对题目进行修改发布的权利，但对每个 Web 题均标明了出处，并将所有用到题目的 repo 连接全部放在了下面，如涉嫌侵权，立马致歉删除。

因为部分题目源码没有 flag ，因此这些题目中的 flag 为我自己随便添加的

对已提供 Dockerfile 及 sql 文件的题目会做适当修改，对于大部分没有的题目，会自己~~编写~~ (复制粘贴)提供相应的文件

# 搭建
每道题目对应的端口需要自行在 run 脚本中更改
```shell
cd the_challenge
chmod 777 build run
./build
./run
```

# 分类

## SQLi

## RCE

[hitcon-2015-babyfirst](https://github.com/inory009/CTF-Web-Challenges/tree/master/RCE/hitcon-2015-babyfirst)

[hitcon-2017-babyfirst-revenge](https://github.com/inory009/CTF-Web-Challenges/tree/master/RCE/hitcon-2017-babyfirst-revenge)

[hitcon-2017-babyfirst-revenge-v2](https://github.com/inory009/CTF-Web-Challenges/tree/master/RCE/hitcon-2017-babyfirst-revenge-v2)

## XSS

## SSRF
[n1ctf-2018-hard-php](https://github.com/inory009/CTF-Web-Challenges/tree/master/SSRF/n1ctf-2018-easy_harder_php)

## Unseralization
[hitcon-2016-babytrick](https://github.com/inory009/CTF-Web-Challenges/tree/master/Unserialization/hitcon-2016-babytrick)

[hitcon-2017-baby^h-master-php](https://github.com/inory009/CTF-Web-Challenges/tree/master/Unserialization/hitcon-2017-baby%5Eh-master-php)

[hitcon-2018-baby-cake](https://github.com/inory009/CTF-Web-Challenges/tree/master/Unserialization/hitcon-2018-baby-cake)

[lctf-2018-babyphp's revenge](https://github.com/inory009/CTF-Web-Challenges/tree/master/Unserialization/lctf-2018-babyphp's-revenge)

## File Inclusion
[XCTF-Final-2018-Bestphp](https://github.com/inory009/CTF-Web-Challenges/tree/master/File-Inclusion/XCTF-Final-2018-Bestphp)

## XXE

# 来源
[l3m0n/My_CTF_Challenges](https://github.com/l3m0n/My_CTF_Challenges)

[wonderkun/CTF_web](https://github.com/wonderkun/CTF_web)

[orangetw/My-CTF-Web-Challenges](https://github.com/orangetw/My-CTF-Web-Challenges)

[l4wio/CTF-challenges-by-me](https://github.com/l4wio/CTF-challenges-by-me)

[otakekumi/CTF-Challenge](https://github.com/otakekumi/CTF-Challenge)

[firesunCN/My_CTF_Challenges](https://github.com/firesunCN/My_CTF_Challenges)

[munsiwoo/ctf-web-prob](https://github.com/munsiwoo/ctf-web-prob)

[hongriSec/CTF-Training](https://github.com/hongriSec/CTF-Training)

[eboda/34c3ctf](https://github.com/eboda/34c3ctf)

[google/google-ctf](https://github.com/google/google-ctf)

[sixstars/starctf2018](https://github.com/sixstars/starctf2018)

[Nu1LCTF/n1ctf-2018](https://github.com/Nu1LCTF/n1ctf-2018)

[zsxsoft/my-rctf-2018](https://github.com/zsxsoft/my-rctf-2018)

[vidar-team/HCTF2017](https://github.com/vidar-team/HCTF2017)

[vidar-team/HCTF2016](https://github.com/vidar-team/HCTF2016)

[LCTF/LCTF2017](https://github.com/LCTF/LCTF2017)

[LCTF/LCTF2018](https://github.com/LCTF/LCTF2018)

[grt1st/LCTF2017-WEB-LPLAYGROUND](https://github.com/grt1st/LCTF2017-WEB-LPLAYGROUND)

[kunte0/Mistune](https://github.com/kunte0/Mistune)