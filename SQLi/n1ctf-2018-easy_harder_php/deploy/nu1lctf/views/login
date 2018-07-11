<?php

if($C->check_login()) {
    header('Location: index.php?action=index');
    exit;
}
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['code'])) {
    if(!isset($_SESSION['code']))
    {
        echo "<script>alert('code error');self.location='index.php?action=login'; </script>";
        exit;
    }
   if($C->login())
   {
       header('Location: index.php?action=index');
       exit;
   }
   else
   {
       unset($_SESSION['code']);
       echo "<script>alert('username or password wrong');self.location='index.php?action=login'; </script>";
       exit;
   }
}
else {
    $code = rand_s(3);
    $md5c = substr(md5($code),0,5);
    $c_view = "substr(md5(?), 0, 5) === $md5c";
    $_SESSION['code'] = $md5c;
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
        <link href="static/bootstrap.min.css" rel="stylesheet">
        <script src="static/jquery.min.js"></script>
        <script src="static/bootstrap.min.js"></script>
    </head>
    <body>
    <div class="container" style="margin-top:100px">
        <form action="index.php?action=login" method="post" class="well" style="width:220px;margin:0px auto;">
            <img src="static/piapiapia.gif" class="img-memeda " style="width:180px;margin:0px auto;">
            <h3>Login</h3>
            <label>Username:</label>
            <input type="text" name="username" style="height:30px"class="span3"/>
            <label>Password:</label>
            <input type="password" name="password" style="height:30px" class="span3">
            <label>Code(<?php echo $c_view?>):</label>
            <input type="text" name="code" style="height:30px" class="span3">
            <button type="submit" class="btn btn-primary">LOGIN</button>
        </form>
    </div>
    </body>
    </html>
    <?php
}
?>
