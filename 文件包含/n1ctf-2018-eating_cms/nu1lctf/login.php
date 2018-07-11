<?php
require_once "function.php";
if($_POST['action'] === 'login'){
    if (isset($_POST['username']) and isset($_POST['password'])){
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $res = login($user,$pass);
        if(!$res){
            Header("Location: index.php");
        }else{
            Header("Location: user.php?page=info");
        }
    }
    else{
        Header("Location: error_parameter.php");
    }
}else if($_REQUEST['action'] === 'logout'){
    logout();
}else{
    Header("Location: error_parameter.php");
}

?>

