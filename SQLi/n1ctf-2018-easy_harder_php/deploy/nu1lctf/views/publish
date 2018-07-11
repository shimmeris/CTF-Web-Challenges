<?php

if(!$C->check_login())
{
    header('Location: index.php?action=login');
    exit;
}
if($C->is_admin==0) {
    if (isset($_POST['signature']) && isset($_POST['mood'])) {
        $res = @$C->publish();
        if($res){
            echo "<script>alert('ok');self.location='index.php?action=index'; </script>";
            exit;
        }
        else {
            echo "<script>alert('something error');self.location='index.php?action=publish'; </script>";
            exit;
        }
    } else {

        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Profile</title>
            <link href="static/bootstrap.min.css" rel="stylesheet">
            <script src="static/jquery.min.js"></script>
            <script src="static/bootstrap.min.js"></script>
        </head>
        <body>
        <a href="index.php?action=logout">logout</a>
        <center>
            <div class="container" style="margin-top:100px">
                <h3>Hi <a href="index.php?action=profile"><?php echo $C->username; ?></a></h3><br>
                <form method='post' action="index.php?action=publish">
                    <p>Please input your signature: <br><textarea name="signature"></textarea></p>
                    <p>Please choose your mood: <br>
                        <input type="radio" name="mood" value="0" checked="checked" /><img src="img/0.gif">
                        <input type="radio" name="mood" value="1" /><img src="img/1.gif">
                        <input type="radio" name="mood" value="2" /><img src="img/2.gif"></p>
                    <input type="submit" value="Submit"/>

                </form>
            </div>
             <a href="index.php?action=index"><img src='img/home.png'></a>
        </center>
        </body>
        </html>
    <?php }

}
else{
    if(isset($_FILES['pic'])){
        $res = @$C->publish();
        if($res){
            echo "<script>alert('ok');self.location='index.php?action=publish'; </script>";
            exit;
        }
        else {
            echo "<script>alert('something error');self.location='index.php?action=publish'; </script>";
        }
    }
    else {

        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Profile</title>
            <link href="static/bootstrap.min.css" rel="stylesheet">
            <script src="static/jquery.min.js"></script>
            <script src="static/bootstrap.min.js"></script>
        </head>
        <body>
        <a href="index.php?action=logout">logout</a>
        <center>
            <div class="container" style="margin-top:100px">
                <h3>Hi <?php echo $C->username; ?></h3><br>
                <form method='post' action="index.php?action=publish" enctype="multipart/form-data">
                    <p>Please upload a pictrue: <br><input name='pic' type='file'></p>
                    <input type="submit" value="Submit"/>

                </form>
            </div>
             <a href="index.php?action=index"><img src='img/home.png'></a>
        </center>
        </body>
        </html>
        <?php
    }
}
?>