<?php

require_once 'config.php';

class Customer{
    public $username, $userid, $is_admin, $allow_diff_ip;

    public function __construct()
    {
        $this->username = isset($_SESSION['username'])?$_SESSION['username']:'';
        $this->userid = isset($_SESSION['userid'])?$_SESSION['userid']:-1;
        $this->is_admin = isset($_SESSION['is_admin'])?$_SESSION['is_admin']:0;
        $this->get_allow_diff_ip();
    }

    public function check_login()
    {
        return isset($_SESSION['userid']);
    }

    public function check_username($username)
    {
        if(preg_match('/[^a-zA-Z0-9_]/is',$username) or strlen($username)<3 or strlen($username)>20)
            return false;
        else
            return true;
    }

    private function is_exists($username)
    {
        $db = new Db();
        @$ret = $db->select('username','ctf_users',"username='$username'");
        if($ret->fetch_row())
            return true;
        else
            return false;
    }

    public function get_allow_diff_ip()
    {
        if(!$this->check_login()) return 0;
        $db = new Db();
        @$ret = $db->select('allow_diff_ip','ctf_users','id='.$this->userid);
        if($ret) {

            $user = $ret->fetch_row();
            if($user)
            {
                $this->allow_diff_ip = (int)$user[0];
                return 1;
            }
            else
                return 0;

        }
    }

    function login()
    {
        if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['code'])) {
            if(substr(md5($_POST['code']),0, 5)!==$_SESSION['code'])
            {
                die("code erroar");
            }
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            if(!$this->check_username($username))
                die('Invalid user name');
            $db = new Db();
            @$ret = $db->select(array('id','username','ip','is_admin','allow_diff_ip'),'ctf_users',"username = '$username' and password = '$password' limit 1");

            if($ret)
            {

                $user = $ret->fetch_row();
                if($user) {
                    if ($user[4] == '0' && $user[2] !== get_ip())
                        die("You can only login at the usual address");
                    if ($user[3] == '1')
                        $_SESSION['is_admin'] = 1;
                    else
                        $_SESSION['is_admin'] = 0;
                    $_SESSION['userid'] = $user[0];
                    $_SESSION['username'] = $user[1];
                    $this->username = $user[1];
                    $this->userid = $user[0];
                    return true;
                }
                else
                    return false;

            }
            else
            {
                return false;
            }

        }
        else
            return false;

    }

    function register()
    {
        if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['code'])) {
            if(substr(md5($_POST['code']),0, 5)!==$_SESSION['code'])
            {
                die("code error");
            }
            $username = $_POST['username'];
            $password = md5($_POST['password']);

            if(!$this->check_username($username))
                die('Invalid user name');
            if(!$this->is_exists($username)) {

                $db = new Db();

                @$ret = $db->insert(array('username','password','ip','is_admin','allow_diff_ip'),'ctf_users',array($username,$password,get_ip(),'0','1')); //No one could be admin except me
                if($ret)
                    return true;
                else
                    return false;

            }

            else {
                die("The username is not unique");
            }
        }
        else
        {
            return false;
        }
    }

    function publish()
    {
        if(!$this->check_login()) return false;
        if($this->is_admin == 0)
        {
            if(isset($_POST['signature']) && isset($_POST['mood'])) {

                $mood = addslashes(serialize(new Mood((int)$_POST['mood'],get_ip())));
                $db = new Db();
                @$ret = $db->insert(array('userid','username','signature','mood'),'ctf_user_signature',array($this->userid,$this->username,$_POST['signature'],$mood));
                if($ret)
                    return true;
                else
                    return false;
            }
        }
        else
        {
                if(isset($_FILES['pic'])) {
                    if (upload($_FILES['pic'])){
                        echo 'upload ok!';
                        return true;
                    }
                    else {
                        echo "upload file error";
                        return false;
                    }
                }
                else
                    return false;


        }

    }

    function showmess()
    {
        if(!$this->check_login()) return false;
        if($this->is_admin == 0)
        {
            //id,sig,mood,ip,country,subtime
            $db = new Db();
            @$ret = $db->select(array('username','signature','mood','id'),'ctf_user_signature',"userid = $this->userid order by id desc");
            if($ret) {
                $data = array();
                while ($row = $ret->fetch_row()) {
                    $sig = $row[1];
                    $mood = unserialize($row[2]);
                    $country = $mood->getcountry();
                    $ip = $mood->ip;
                    $subtime = $mood->getsubtime();
                    $allmess = array('id'=>$row[3],'sig' => $sig, 'mood' => $mood, 'ip' => $ip, 'country' => $country, 'subtime' => $subtime);
                    array_push($data, $allmess);
                }
                $data = json_encode(array('code'=>0,'data'=>$data));
                return $data;
            }
            else
                return false;

        }
        else
        {
            $filenames = scandir('adminpic/');
            array_splice($filenames, 0, 2);
            return json_encode(array('code'=>1,'data'=>$filenames));

        }
    }

    function allow_diff_ip_option()
    {
        if(!$this->check_login()) return false;
        if($this->is_admin == 0)
        {
            if(isset($_POST['adio'])){
                $db = new Db();
                @$ret = $db->update_single('ctf_users',"id = $this->userid",'allow_diff_ip',(int)$_POST['adio']);
                if($ret)
                    return true;
                else
                    return false;
            }
        }
        else
            echo 'admin can\'t change this option';
            return false;
    }

    function deletemess()
    {
        if(!$this->check_login()) return false;
        if(isset($_GET['delid'])) {
            $delid = (int)$_GET['delid'];
            $db = new Db;
            @$ret = $db->delete('ctf_user_signature', "userid = $this->userid and id = '$delid'");
            if($ret)
                return true;
            else
                return false;
        }
        else
            return false;
    }

}
