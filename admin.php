<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="assete/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body dir="rtl">
    <?php

    include("config.php");
    include("app.php");
    $db = mysqli_connect(hostname:$information['HOST'] ,username:$information['USERNAME'],password:$information['PASSWORD'],database:$information['DATABASE'] );
    include("header.php");
    ?> 
 
    <div class="row">
        <div class="col-md-4 col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">لیست کاربران</h2>
                    <a href="admin.php?job=read_user" class="btn btn-primary">بازکردن</a>
                </div>
            </div>
        </div>
 
        <div class="col-md-4 col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">حذف وبلاگ</h2>
                    <a href="admin.php?job=delete_blog" class="btn btn-primary">بازکردن</a>
                </div>
            </div>
        </div>
 
        <div class="col-md-4 col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">حذف نویسنده</h2>
                    <a href="admin.php?job=delete_writer" class="btn btn-primary">بازکردن </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">گزارش ها</h2>
                    <a href="admin.php?job=read_report" class="btn btn-primary">بازکردن </a>
                </div>
            </div>
        </div>
 
    </div>
    <div class="container">
    <?php
    if (isset($_GET['job']) && $_GET['job'] == "delete_report"){
        $fids = $_POST['fid'];
        foreach($fids as $fid){
            $query = "DELETE FROM feedback WHERE fid = $fid;";
            mysqli_query($db , $query);
        }
        $msg = "گزارش ها پاک شدند";
        show_report($db , $msg);

    }
    if (isset($_GET['job']) && $_GET['job'] == "read_report"){
        $msg = NULL;
        show_report($db , $msg);
    }

    if (isset($_GET['job']) && $_GET['job'] == 'read_user'){
        show_users($db);
    }elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_GET["job"]) && $_GET['job'] == "delete_user"){
        $users = $_POST['delete'];
        foreach($users as $user){
            $query = "DELETE FROM users WHERE userid = $user;";
            mysqli_query($db , $query);
        }
        $msg = "کاربران پاک شدند";
        show_users($db , $msg);

    }elseif (isset($_GET["job"]) && $_GET['job'] == "delete_blog"){
        if (isset($_POST['delete_blog'])) {
            $blogs = $_POST['delete_blog'];
            foreach($blogs as $blog){
                $query_delete_blog = "DELETE FROM blogs WHERE bid = $blog";
                mysqli_query($db , $query_delete_blog);
                $query_delete_blog = "DELETE FROM feedback WHERE bid = $blog";
                mysqli_query($db , $query_delete_blog);
            }
            $msg = "مقاله ها پاک شدند";
        }
        $query_get_blogs = "SELECT blogs.bid,blogs.title, blogs.date_time,blogs.seen,category.c_name,users.first_name,users.last_name,users.username FROM blogs INNER JOIN category ON blogs.cid = category.cid INNER JOIN users ON blogs.wid = users.userid;";
        $result = mysqli_query($db , $query_get_blogs);
        ?> 
        <form action="?job=delete_blog" method="post">
          <?php 
            if (isset($msg)) {
                ?>
                <ul>
                    <li class="alert alert-sucsess"><?=$msg?></li>
                </ul>
                <?php
            }
            ?>
            <table class="table table-striped" border="1">
                <thead>
                    <th>حذف</th>
                    <th>سربرگ</th>
                    <th>تاریخ</th>
                    <th>تعداد بازدید</th>
                    <th>دسته بندی</th>
                    <th>نام نویسنده</th>
                    <th>نام خانوادگی نویسنده</th>
                    <th>نام کاربری نویسنده</th>
                </thead>
                <tbody>
                    <?php 
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?> 
                        <tr>
                            <div class="form-check">
                                <td>
                                    <input type="checkbox" class="form-check-input" name="delete_blog[]" value="<?=$row['bid']?>">
                                </td>
                            </div>
                            <td class="form-check-label" ><a href="blogs.php?id=<?=$row['bid']?>"><?=$row['title']?></a></td>
                            <td class="form-check-label" ><?=$row['date_time']?></td>
                            <td class="form-check-label" ><?=$row['seen']?></td>
                            <td class="form-check-label" ><?=$row['c_name']?></td>
                            <td class="form-check-label" ><?=$row['first_name']?></td>
                            <td class="form-check-label" ><?=$row['last_name']?></td>
                            <td class="form-check-label" ><?=$row['username']?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit" value="حذف وبلاگ" class="btn btn-primary">
        </form>        
        <?php
    }elseif ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET["job"]) && $_GET['job'] == "delete_writer"){
        if (isset($_POST['wid'])){}
        $query_get_writers = "SELECT * FROM users WHERE rol = 'writer';";
        $result = mysqli_query($db , $query_get_writers);
        ?>
            <form action="?job=delete_user" method="get">
                <?php 
                    if (isset($msg)) {
                        ?>
                        <ul>
                            <li class="alert alert-sucsess"><?=$msg?></li>
                        </ul>
                        <?php
                    }
                ?>
                <table class="table table-striped" border="1">
                    <thead>                
                        <tr>
                            <th>حذف</th>
                            <th>نام</th>
                            <th>نام</th>
                            <th>نام خانوادگی</th>
                            <th>جیمیل</th>
                            <th>عکس پروفایل</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?> 
                        <tr>
                            <div class="form-check">
                                <td>
                                    <input type="checkbox" name="wid[]" value="<?=$row['userid']?>" class="form-check-input">
                                </td>
                            </div>
                            <td class="form-check-label"><?=$row['username']?></td>
                            <td class="form-check-label"><?=$row['first_name']?></td>
                            <td class="form-check-label"><?=$row['last_name']?></td>
                            <td class="form-check-label"><?=$row['gmail']?></td>
                            <td><img src="<?=$row['profile_']?>" class="img-style" alt="profile_photo"></td>
                        
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <input type="submit" value="حذف کاربران" class="btn btn-primary">
            </form>
        <?php
    }

    ?>
    </div>
</body>
</html>