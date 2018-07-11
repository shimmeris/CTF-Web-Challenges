<?php
require_once "function.php";
if($_POST['action'] === 'register'){
    if (isset($_POST['username']) and isset($_POST['password'])){
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $res = register($user,$pass);
        if($res){
            Header("Location: index.php");
        }else{
            $errmsg = "Username has been registered!";
        }
    }
    else{
        Header("Location: error_parameter.php");
    }
}
if (!$_SESSION['login']) {
    include "templates/register.html";
} else {
    Header("Location : user.php?page=info");
}

?>
