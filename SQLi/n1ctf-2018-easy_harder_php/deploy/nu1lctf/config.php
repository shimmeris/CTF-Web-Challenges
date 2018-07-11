<?php
header("Content-Type:text/html;charset=UTF-8");
date_default_timezone_set("PRC");

session_start();
class Db
{
    private  $servername = "localhost";
    private  $username = "Nu1L";
    private  $password = "Nu1Lpassword233334";
    private  $dbname = "nu1lctf";
    private  $conn;

    function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

    function __destruct()
    {
        $this->conn->close();
    }

    private function get_column($columns){

        if(is_array($columns))
            $column = ' `'.implode('`,`',$columns).'` ';
        else
            $column = ' `'.$columns.'` ';

        return $column;
    }

    public function select($columns,$table,$where) {

        $column = $this->get_column($columns);

        $sql = 'select '.$column.' from '.$table.' where '.$where.';';
        $result = $this->conn->query($sql);

        return $result;

    }

    public function insert($columns,$table,$values){

        $column = $this->get_column($columns);
        $value = '('.preg_replace('/`([^`,]+)`/','\'${1}\'',$this->get_column($values)).')';
        $nid =
        $sql = 'insert into '.$table.'('.$column.') values '.$value;
        $result = $this->conn->query($sql);

        return $result;
    }

    public function delete($table,$where){

        $sql =  'delete from '.$table.' where '.$where;
        $result = $this->conn->query($sql);

        return $result;
    }

    public function update_single($table,$where,$column,$value){

        $sql = 'update '.$table.' set `'.$column.'` = \''.$value.'\' where '.$where;
        $result = $this->conn->query($sql);

        return $result;
    }




}

class Mood{

    public $mood, $ip, $date;

    public function __construct($mood, $ip) {
        $this->mood = $mood;
        $this->ip  = $ip;
        $this->date = time();

    }

    public function getcountry()
    {
        $ip = @file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$this->ip);
        $ip = json_decode($ip,true);
        return $ip['data']['country'];
    }

    public function getsubtime()
    {
        $now_date = time();
        $sub_date = (int)$now_date - (int)$this->date;
        $days = (int)($sub_date/86400);
        $hours = (int)($sub_date%86400/3600);
        $minutes = (int)($sub_date%86400%3600/60);
        $res = ($days>0)?"$days days $hours hours $minutes minutes ago":(($hours>0)?"$hours hours $minutes minutes ago":"$minutes minutes ago");
        return $res;
    }

    
}

function get_ip(){
    return $_SERVER['REMOTE_ADDR'];
}

function upload($file){
    $file_size  = $file['size'];
    if($file_size>2*1024*1024) {
        echo "pic is too big!";
        return false;
    }
    $file_type = $file['type'];
    if($file_type!="image/jpeg" && $file_type!='image/pjpeg') {
        echo "file type invalid";
        return false;
    }
    if(is_uploaded_file($file['tmp_name'])) {
        $uploaded_file = $file['tmp_name'];
        $user_path =  "/app/adminpic";
        if (!file_exists($user_path)) {
            mkdir($user_path);
        }
        $file_true_name = str_replace('.','',pathinfo($file['name'])['filename']);
        $file_true_name = str_replace('/','',$file_true_name);
        $file_true_name = str_replace('\\','',$file_true_name);
        $file_true_name = $file_true_name.time().rand(1,100).'.jpg';
        $move_to_file = $user_path."/".$file_true_name;
        if(move_uploaded_file($uploaded_file,$move_to_file)) {
            if(stripos(file_get_contents($move_to_file),'<?php')>=0)
                system('sh /home/nu1lctf/clean_danger.sh');
            return $file_true_name;
        }
        else
            return false;
    }
    else
        return false;
}
function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}
function rand_s($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
    $password = '';
    for ( $i = 0; $i < $length; $i++ )
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}

function addsla_all()
{
    if (!get_magic_quotes_gpc())
    {
        if (!empty($_GET))
        {
            $_GET  = addslashes_deep($_GET);
        }
        if (!empty($_POST))
        {
            $_POST = addslashes_deep($_POST);
        }
        $_COOKIE   = addslashes_deep($_COOKIE);
        $_REQUEST  = addslashes_deep($_REQUEST);
    }
}
addsla_all();

