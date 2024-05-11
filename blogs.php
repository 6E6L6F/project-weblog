<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assete/css/styles.css">

</head>
<body dir="rtl">
    <?php 
    function get_like($db , $userid){
        if(isset($userid)){
            $query_check_like = "SELECT SUM(like_) FROM feedback WHERE userid = $userid";
            $result_like = mysqli_query($db , $query_check_like);
            $like = mysqli_fetch_all($result_like);
            return $like;
        } 
    }

    include("config.php");
    $db = mysqli_connect(hostname:$information['HOST'] ,username:$information['USERNAME'],password:$information['PASSWORD'],database:$information['DATABASE'] );
    
    if (!$db) {
        echo "connections database fiald..!";
        exit(0);
    }

    include("header.php");
    if(isset($_SESSION['userid'])){
        $userid = $_SESSION['userid'];
    }else {
        $userid = NULL;
    }
    if (isset($_GET['id'])){
        $bid = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['report'])){
                $report_message = $_POST['report'];
                $query_write_feedback = "INSERT INTO feedback(comment , like_ , bid ,userid ,report) VALUES('' , '0' , '$bid' , '$userid' , '$report_message');";
                $result = mysqli_query($db, $query_write_feedback);
            } 
                $like = 0;
                $comment = "";
                
                if (isset($_SESSION['userid'])) {

                    if (isset($_POST['like'])) {
                        $like = 1;
                    }
                    if (isset($_POST['comment'])) {
                        $comment = $_POST['comment'];
                    }
                    $userid = $_SESSION['userid'];
                    $like_ = get_like($db , $userid);
                    if ($like_[0][0] == 1){
                        $query_write_feedback = "INSERT INTO feedback(comment , like_,bid ,userid ,report) VALUES('$comment' , '0' , '$bid' , '$userid' , '')";
                        $result = mysqli_query($db, $query_write_feedback);
                    
                    }else {
                        $query_write_feedback = "INSERT INTO feedback(comment , like_,bid ,userid ,report) VALUES('$comment' , '$like' , '$bid' , '$userid' , '')";
                        $result = mysqli_query($db, $query_write_feedback);
                    }
                    $message_feedback = "نظر شما ثبت شد";    
                } else {
                    $message_feedback = "برای ثبت نظر باید وارد حساب کاربری شوید";
                }
        }
    }
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        $query_get_post = "SELECT * FROM blogs INNER JOIN media ON blogs.bid = media.bid INNER JOIN category ON blogs.cid = category.cid WHERE blogs.bid = $id;";
        $result = mysqli_query($db , $query_get_post);
        $info = mysqli_fetch_assoc($result);
        if (!empty($info)) {
            ?> 
                <div class="containers">
                    <div class="title">
                        <h2><?=$info["title"]?></h2>
                    </div>
                    <img src="<?=$info["photo"]?>" class="w-100" style="height: 600px;"> 
                    <div class="blogs">
                        <div class="abstract">
                            <span><?=$info["abstract"]?></span>
                        </div>
                            <p class="dsec"><?=$info["long_description"]?></p>
                            <p class="dsec">بازدید : <?=$info['seen']?></p>
                            <p class="dsec">تاریخ انتشار : <?=$info['date_time']?></p>
                            <span>دسته بندی : <?=$info['c_name']?></span>
                        </div>
                        <div class="album">
                    <?php
                 
                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($row["format"] == "images") {
                                ?> 
                                
                                  <img src="<?=$row['path_file']?>" alt='photo'> 
                               
                                <?php                                
                            }
                        }
                            ?>
                             </div>
                </div>
                    <form action="" method="post" class="from-comment">
                        <?php 
                        if (isset($message_feedback) || !empty($message_feedback)) {
                            ?> 
                            <ul>
                                <li><?=$message_feedback?></li>
                            </ul>
                            <?php
                        }
                        $query_get_comment = "SELECT feedback.comment , users.username , users.profile_ , SUM(feedback.like_) AS c_like FROM feedback INNER JOIN users ON users.userid = feedback.userid WHERE feedback.bid = $id GROUP BY feedback.comment;";
                        $result = mysqli_query($db , $query_get_comment);
                        $infos = mysqli_fetch_assoc($result);
                        $like = get_like($db , $userid);
                    
                        if ($like[0][0] == 1) {
                            
                            ?> 
                                <label for="like">لایک ها :  <?php if(!empty($infos["c_like"])) {echo $infos["c_like"];} else {echo "0";}?></label>
                            <?php
                        } else {

                            ?> 
                            <div class="form-group">
                                <label for="like">لایک ها :  <?php if(!empty($infos["c_like"])) {echo $infos["c_like"];} else {echo "0";}?></label>
                                <input type="submit" value="لایک کردن" name="like" class="btn btn-primary">
                            </div>                            
                            <?php
                        }
                        ?>


                        <div class="form-group">
                            <label for="comment">نظرات: </label>
                            <textarea class="form-control" id="comment" name="comment" rows="5"></textarea>
                        </div>
                        <input type="submit" class="btn btn-primary" value="ارسال نظر" name="c">

                        <div class="form-group">
                            <label for="report">گزارش: </label>
                            <textarea class="form-control" id="report" name="report" rows="5"></textarea>
                        </div>
                        <input type="submit" class="btn btn-primary" value="ارسال گزارش" name="r">

                    </form> 
                    
                    <div class="comments">
                        <h2>نظرات کاربران</h2>
                        <?php 
                        while($row = mysqli_fetch_assoc($result)){
                            ?> 
                            <div class="messages">
                                <img src="<?=$row['profile_']?>">
                                <span><?=$row['username']?></span>
                                <p><?=$row['comment']?></p>
                            </div>
                            <?php
                        }
                        $new_seen = $info['seen'] + 1;  
                        $query_add_seen = "UPDATE blogs SET seen = $new_seen WHERE bid = $id;";
                        $res = mysqli_query($db , $query_add_seen);
                        ?>
                    </div>
                </div>
            <?php     
        }else {
            ?> 
            <div class="container">
                <div class="title">
                    <h2>این وبلاگ پیدا نشد احتمالا توسط ادمین حذف شده است ایا به دنبال چیز دیگری میگردید؟جستوجو کنید</h2>
                </div>
            </div>
            <?php
        }
    }
    include("footer.php");
    mysqli_close($db);
    ?>
</body>
</html>