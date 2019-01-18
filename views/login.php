<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashcam - Login</title>
    <?php include_once('head.php') ?>

</head>
<body>

<?php include_once('navigation.php') ?>


<div class="content">
   <div class="contentBlock">
        <div class="contentBlockTitle">Login</div>
        <div>
            <form action="/session/login" method="post">
                Username<br> <input type="text" name="Username" value=""><br>
                Password<br> <input type="password" name="Password" value="">
                <br><br>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>

</div>


</body>
</html>