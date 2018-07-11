<?php
require_once("function.php");
if( !isset( $_SESSION['user'] )){
    Header("Location: index.php");

}
//die($_SESSION['isadmin']);
if (($_REQUEST['username'])) {

    if ($_SESSION['isadmin'] === '1') {
        $user2up = $_REQUEST['username'];
//    die($user2up);
        $ress = updateadmin('1', $user2up);
//    die('11111');
//    die($ress);
        if ($ress == 1) {
//        die('1111111sdfdsfs');
            echo "<script>alert('update success')</script>";
        } else {
            echo "<script>alert('update fail')</script>";
        }
    } else {
        Header("Location: index.php");
    }
}
//if(!in_array($page,$oper_you_can_do)){
//    $page = 'info';
//}
?>