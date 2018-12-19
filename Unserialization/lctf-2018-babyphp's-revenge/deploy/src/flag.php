session_start();
echo 'only localhost can get flag!';
$flag = 'LCTF{*************************}';
if($_SERVER["REMOTE_ADDR"]==="127.0.0.1"){
       $_SESSION['flag'] = $flag;
   }
<?php
session_start();
echo 'only localhost can get flag!';
$flag = 'LCTF{funny_s3ss1on_4nd_S0ap}';
if ($_SERVER["REMOTE_ADDR"] === "127.0.0.1") {
	$_SESSION['flag'] = $flag;
}
?>