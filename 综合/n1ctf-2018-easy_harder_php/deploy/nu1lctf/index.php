<?php

require_once 'user.php';
$C = new Customer();
if(isset($_GET['action']))
require_once 'views/'.$_GET['action'];
else
header('Location: index.php?action=login');
