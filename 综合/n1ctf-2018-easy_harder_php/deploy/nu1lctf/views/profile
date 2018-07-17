<?php

if(!$C->check_login())
{
    header('Location: index.php?action=login');
    exit;
}
if(isset($_POST['adio'])){
    $res = @$C->allow_diff_ip_option();
    if($res){
    echo "<script>alert('ok');self.location='index.php?action=profile'; </script>";
    exit;
    }
    else
    {
        echo "<script>alert('something error');self.location='index.php?action=profile'; </script>";
        exit;
    }
}
else{

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
            <h3>Hi <a href="index.php?action=profile"><?php echo $C->username;?></a></h3><br>
            <form method='post' action="index.php?action=profile">
                <p></p>
                <input type="radio" name="adio" value="1" <?php if($C->allow_diff_ip == 1) echo 'checked="checked"';?> />Allow different ip to login
                <input type="radio" name="adio" value="0" <?php if($C->allow_diff_ip == 0) echo 'checked="checked"';?>/>Don't allow
                <input type="submit" value="Submit" />

            </form>
        </div>
         <a href="index.php?action=index"><img src='img/home.png'></a>
    </center>
    </body>
    </html>
<?php }?>