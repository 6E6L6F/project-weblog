<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="assete/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 
    <title>Index</title>
</head>
<body>
    <?php 
    include("config.php");
    include("app.php");
    $db = mysqli_connect(hostname:$information['HOST'] ,username:$information['USERNAME'],password:$information['PASSWORD'],database:$information['DATABASE'] );
    if (!$db) {
        echo "connections database fiald..!";
        exit(0);
    }
    include("header.php");

    ?>
         <div class="">
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search'])){
        $text = $_POST['search'];
        ?> 
    
        <div class="row"> 
          <h2 class="title">نتایج جستوجو</h2>
        <?php
            $query_search = "SELECT bid, abstract, title, photo, c_name , date_time , seen FROM blogs INNER JOIN category ON blogs.cid = category.cid  WHERE long_description LIKE '%$text%';;";
            execute_query($query_search , $db);
        ?> </div> <?php

    } else {

        if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['category'])) {
            $c_id = $_GET['category'];
            ?> 
            <div class="row">
            <h2 class="title">وبلاگ های این دسته بندی</h2>
            <?php
                $query_get_one_category = "SELECT bid, abstract, title, photo, c_name , date_time , seen FROM blogs INNER JOIN category ON blogs.cid = category.cid WHERE category.cid = $c_id"; 
                execute_query($query_get_one_category , $db);
            ?> </div> <?php
        } else {
            ?> 
            <div class="row">
                <h2 class="title">جدید ترین وبلاگ ها</h2>    
               
                <?php
                    $query_get_last_posts = "SELECT bid, abstract, title, photo  FROM blogs INNER JOIN category ON blogs.cid = category.cid ORDER BY  blogs.bid DESC LIMIT 10;";
                    $result = mysqli_query($db ,$query_get_last_posts); 
                ?>
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <?php 
                            $row = mysqli_fetch_assoc($result);

                            ?> 
                            <div class="carousel-item active">
                                <a href="blogs.php?id=<?=$row['bid']?>"><img class="d-block w-100 img-car" src="<?=$row['photo']?>">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5><?=$row['title']?></h5>
                                    <p><?=$row['abstract']?></p>
                                </div>
                                </a>
                            </div>
                            
                            <?php
                            while($row = mysqli_fetch_assoc($result)){
                                ?> 
                                    <div class="carousel-item">
                                        <a href="blogs.php?id=<?=$row['bid']?>"><img class="d-block w-100 img-car" src="<?=$row['photo']?>">
                                        <div class="carousel-caption d-none d-md-block">
                                                <h5><?=$row['title']?></h5>
                                                <p><?=$row['abstract']?></p>
                                            </div>
                                        </a>
                                    </div>
                                
                                <?php
                            }
                            
                            ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        </div>
                </div>

            <div class="row"> 
            <h2 class="title">بیشترین بازدید ها</h2>
            <?php
                $query_get_best_seen_posts = "SELECT bid, abstract, title, photo, c_name , date_time , seen FROM blogs  INNER JOIN category ON blogs.cid = category.cid WHERE blogs.seen > 10 ORDER BY blogs.bid DESC LIMIT 6;";
                execute_query($query_get_best_seen_posts , $db);
            ?>
            </div>
            
            <div class="row"> 
            <h2 class="title">بیشترین لایک ها</h2>
            <?php
                $query_get_best_like_posts = "SELECT blogs.bid, blogs.abstract, blogs.title, blogs.photo, category.c_name , blogs.date_time , blogs.seen FROM blogs JOIN category ON blogs.cid = category.cid JOIN feedback ON blogs.bid = feedback.bid GROUP BY blogs.bid HAVING COUNT(feedback.like_) > 1 ;";
                execute_query($query_get_best_like_posts , $db);
            ?> 
            </div> 
 
            <div class="row">
                <h2 class="title">دسته بندی ها</h2>
                <?php
                    $query_get_categroy = "SELECT * FROM category;";
                    $result = mysqli_query($db ,$query_get_categroy);
                    while ($row = mysqli_fetch_assoc($result)){
                        ?> 
                            <div class="card">
                                <a href="?category=<?=$row["cid"]?>"><h3 class="bg-light"><?=$row["c_name"]?></h3></a>
                            </div>
                        <?php
                    }?> 
            </div> <?php
        }
    }
    mysqli_close($db);
    ?>
    <?php
    include("footer.php");
    
    ?>
            </div>
    <?php
    ?>
</body>
</html>