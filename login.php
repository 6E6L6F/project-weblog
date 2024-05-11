<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assete/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login Form</title>
</head>
<body class="container" dir="rtl">
    <?php
     
    include("config.php");
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
        $db = mysqli_connect(hostname:$information['HOST'] ,username:$information['USERNAME'],password:$information['PASSWORD'],database:$information['DATABASE'] );
        if (!$db) {
            echo "connections database fiald..!";
            exit(0);
        }
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT passwd,userid,rol FROM users WHERE username = '$username';";
        $result = mysqli_fetch_assoc(mysqli_query($db , $query));
        $passwd = hash('sha384', $password);
        if (isset($result['passwd']) && $result['passwd'] == $passwd) {
            $_SESSION['userid'] = $result['userid'];
            $_SESSION['rol'] = $result['rol'];
            mysqli_close($db);
            header('location:index.php');
        }else {
            $msg = "نام کاربری یا پسورد اشتباه است";
        }
        mysqli_close($db);
    }
    ?>
    <div class="from-style">
        <form action="" method="post">
            <?php 
            if (isset($msg)) {
                ?>
                <ul>
                    <li class="alert alert-danger"><?=$msg?></li>
                </ul>
                <?php
            }
            ?>
            <div class="mb-3 mt-3">
                <label for="username" class="form-label">نام کاربری:</label>
                <input type="text" class="form-control" id="username" placeholder="نام کاربری را وارد کنید" name="username">
                </div>
            <div class="mb-3">
                <label for="password" class="form-label">پسورد:</label>
                <input type="password" class="form-control" id="password" placeholder="پسورد را وارد کنید" name="password">
            </div>

            <button type="submit" class="btn btn-primary">ورود</button>
        </form>
        </div> 
</body>
</html>