<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assete/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>پروفایل کاربر</title>
</head>
<body>
    <?php 
    include("config.php");
    $userid = $_SESSION['userid'];

    $db = mysqli_connect(hostname:$information['HOST'] ,username:$information['USERNAME'],password:$information['PASSWORD'],database:$information['DATABASE'] );
    if (!$db) {
        echo "connections database fiald..!";
        exit(0);
    }
    if (isset($_SESSION['userid'])) {
        include("header.php");
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if(isset($_GET['job']) && $_GET['job'] == "edit_profile"){
                if(isset($_POST['password']) && !empty($_POST['password'])){
                    $passwd = hash('sha384', $password);
                    $query = "UPDATE users SET passwd='$passwd' WHERE userid = $userid";
                    mysqli_query($db , $query);
                }
                if (isset($_FILES['profile']['name'])){
                    $tmp_photo = $_FILES['profile']['tmp_name'];
                    $profile_path = "photo/profile/" . $_FILES['profile']['name'];
                    move_uploaded_file($tmp_photo , $profile_path);
                    $query = "UPDATE users SET profile_='$profile_path' WHERE userid = $userid";
                     mysqli_query($db , $query);
                   
                }
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gmail = $_POST['gmail'];
                $query_update = "UPDATE users SET first_name='$first_name',last_name='$last_name',gmail='$gmail' WHERE userid = $userid;";
                mysqli_query($db , $query_update);
                $msg = "اطلاعات شما به روز رسانی شد";
            }
        }
        if (isset($_GET['job']) && $_GET['job'] == "like_list"){
            $query_get_liked_post = "SELECT bid FROM feedback WHERE userid = $userid AND like_ = 1;";
            $result = mysqli_query($db , $query_get_liked_post);
            ?> 
            <div class="row">
            <?php
            while ($row_ids = mysqli_fetch_assoc($result)) {
                if (!empty($row_ids)) {
                    $bid = $row_ids['bid'];
                    $query_get_posts = "SELECT bid,title,abstract,photo FROM blogs WHERE bid = $bid;";
                    $result_query = mysqli_query($db , $query_get_posts);
                    while ($row_post = mysqli_fetch_assoc($result_query)){
                        ?> 
                        <div class="col-md-3 col-lg-2 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title"><?=$row_post["title"]?></h2>
                                    <div class="img-index">
                                        <a href="blogs.php?id=<?=$row_post["bid"]?>"> <img src="<?=$row_post["photo"]?>" class="card-img-top"> </a> 
                                    </div>
                                    <p class="card-text"><?=$row_post["abstract"]?></p>
                                    <a href="blogs.php?id=<?=$row_post["bid"]?>" class="btn btn-primary">بازدید</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }else {
                    echo "شما هنوز پستی لایک نکردید لطفا برای حمایت ما وبلاگ هارا لایک کنید ";
                }
            }
            ?> 
                </div>
            <?php
        } elseif (isset($_GET['job']) && $_GET['job'] == "edit_profile"){
            $query_get_info = "SELECT first_name,last_name,passwd,profile_,gmail FROM users WHERE userid = $userid;";
            $result = mysqli_fetch_assoc(mysqli_query($db , $query_get_info));
            ?> 
            <div class="from-style">
                <form action="" method="post" enctype="multipart/form-data">
                    <?php 
                    if (isset($msg)) {
                        ?>
                        <ul>
                            <li><?=$msg?></li>
                        </ul>
                        <?php
                    }
                    ?>
                    <div class="mb-3 mt-3">
                        <label for="first_name" class="form-label">نام:</label>
                        <input type="text" class="form-control" id="first_name" value="<?= $result["first_name"]?>" placeholder="نام خود را وارد کنید" name="first_name">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="last_name" class="form-label">نام خانوادگی:</label>
                        <input type="text" class="form-control" id="last_name" value="<?= $result["last_name"]?>"  placeholder=" نام خانوادگی خود را وارد کنید"  name="last_name">
                    </div>
                
                    <div class="mb-3 mt-3">
                        <label for="email" class="form-label">جیمیل:</label>
                        <input type="email" class="form-control" id="gmail" value="<?= $result["gmail"]?>" placeholder="جیمیل خود را وارد کنید" name="gmail">
                    </div>
                
                <div class="mb-3 mt-3">
                        <label for="profile" class="form-label">عکس پروفایل:</label>
                        <input class="form-control" type="file" id="profile" name="profile"/>
                </div>
                
                    <div class="mb-3">
                        <label for="password" class="form-label">پسورد:</label>
                        <input type="password" class="form-control" id="password"  placeholder="Enter password" name="password">
                    </div>

                    <button type="submit" class="btn btn-primary">اعمال تغییرات</button>
                </form>
                </div> 
            <?php
        }
    }
    ?>
</body>
</html>