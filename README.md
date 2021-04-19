**本项目不再维护和更新**

本项目只是对历届 CTF 开源的 Web 题源码进行了一个整理分类，并提供一个简单的搭建方法

# 申明
由于本人并未向出题人申请重新对题目进行修改发布的权利，但对每个题均标明了出处，如涉嫌侵权，立马致歉删除。

对于部分没找到 flag 的题目，会自己随便添加

对已提供 Dockerfile 及 sql 文件的题目会做适当修改(或者重写，因为有的题目按给的文件运行不起来。。可能是我打开的方式不对），对于大部分没有的题目，会自己~~编写~~ (复制粘贴)提供相应的文件

如有 bug，还望告知

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
[34c3-2017-urlstorage](https://github.com/inory009/CTF-Web-Challenges/tree/master/XSS/34c3-2017-urlstorage)


## SSRF
[n1ctf-2018-hard-php](https://github.com/inory009/CTF-Web-Challenges/tree/master/SSRF/n1ctf-2018-easy_harder_php)

[34c3-2017-extract0r](https://github.com/inory009/CTF-Web-Challenges/tree/master/SSRF/34c3-2017-extract0r)


## Unseralization
[hitcon-2016-babytrick](https://github.com/inory009/CTF-Web-Challenges/tree/master/Unserialization/hitcon-2016-babytrick)

[hitcon-2017-baby^h-master-php](https://github.com/inory009/CTF-Web-Challenges/tree/master/Unserialization/hitcon-2017-baby%5Eh-master-php)

[hitcon-2018-baby-cake](https://github.com/inory009/CTF-Web-Challenges/tree/master/Unserialization/hitcon-2018-baby-cake)

[lctf-2018-babyphp's revenge](https://github.com/inory009/CTF-Web-Challenges/tree/master/Unserialization/lctf-2018-babyphp's-revenge)

## File Inclusion
[XCTF-Final-2018-Bestphp](https://github.com/inory009/CTF-Web-Challenges/tree/master/File-Inclusion/XCTF-Final-2018-Bestphp)

[hitcon-2018-one-line-php-challenge](https://github.com/inory009/CTF-Web-Challenges/tree/master/File-Inclusion/hitcon-2018-one-line-php-challenge)

## XXE

## SSTI
