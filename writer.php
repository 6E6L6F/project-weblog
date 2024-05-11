<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assete/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>پنل نویسنده</title>
</head>
<body dir="rtl">
    <?php 
    
    include("config.php");
        if(isset($_SESSION['userid']) && isset($_SESSION['rol']) && $_SESSION['rol'] == "writer"){
            $db = mysqli_connect(hostname:$information['HOST'] ,username:$information['USERNAME'],password:$information['PASSWORD'],database:$information['DATABASE'] );
            if (!$db) {
                echo "connections database fiald..!";
                exit(0);
            }
            include("header.php");
            include("app.php");
            ?> 
                <div class="container">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    if (isset($_GET['upload_media'])) {
                        $bid = $_POST['post_id'];
                        $tmp_file = $_FILES['files']['tmp_name'];
                        
                        if (explode("/", $_FILES['files']['type'])[0] == "image") {
                            $path = "photo/media/" . $bid . $_FILES['files']['name'];
                            $format = "images";
                            move_uploaded_file($tmp_file , $path);
                            $query_insert_media = "INSERT INTO media(path_file , bid , format) VALUES('$path' , '$bid' , '$format');";
                            mysqli_query($db , $query_insert_media);
                            $msg = "فایل اضافه شد";
                        }else {
                            $msg = "مشکلی توی ضمینه کردن فایل به وجود امد";
                        }  
                    } else {
                        $title = $_POST['title'];
                        $abstract = $_POST['abstract'];
                        $date = $_POST['date_time'];
                        $long_desc = $_POST['long_desc'];
                        
                        $category_name = $_POST['category'];
                        $query_get_category_id = "SELECT cid FROM category WHERE c_name LIKE '%$category_name%';";
                        $result_query = mysqli_query($db , $query_get_category_id);
                        $result = mysqli_fetch_assoc($result_query);
                        if($result) {
                            $category = $result['cid'];
                        }else {
                            $query = "INSERT INTO category(c_name) VALUES('$category_name');";
                            mysqli_query($db , $query);
                        }

                        if(!isset($category)) {
                            $query_get_category_id = "SELECT cid FROM category WHERE c_name LIKE '%$category_name%';";
                            $result_query = mysqli_query($db , $query_get_category_id);
                            $category = mysqli_fetch_assoc($result_query)['cid'];
                        }
                        
                        $tmp_photo = $_FILES['photo']["tmp_name"];
                        $new_path = "photo/media/" . $_FILES['photo']['name'];
                        move_uploaded_file($tmp_photo , $new_path);
                        $writer = $_SESSION['userid'];
                        $query_create_post = "INSERT INTO blogs(abstract ,long_description , title , photo , date_time , wid , cid , seen) VALUES('$abstract' , '$long_desc ' ,'$title' ,'$new_path' , '$date' , '$writer' , '$category', 0);";
                        mysqli_query($db , $query_create_post);
                        $msg = "پست شما ساخته شد..!";
                    }
            }
            if (isset($_GET['job']) && $_GET['job'] == "feedbacks"){
                $wid = $_SESSION['userid'];
                show_feedbacks($db , $wid);

            }elseif (isset($_GET['upload_media'])){
                $wid = $_SESSION['userid'];
                $query_get_post_list = "SELECT bid,title FROM blogs WHERE wid = $wid;";
                $result = mysqli_query($db , $query_get_post_list);
                ?>
                <form action="?upload_media=true" method="post" enctype="multipart/form-data" class="from-style">
                    <?php 
                    if (isset($msg)){
                        ?> 
                        <ul>
                            <li><?=$msg?></li>
                        </ul>
                        <?php
                    }
                    ?>
                    <div class="mb-3 mt-3">
                    <select name="post_id" id="post_id"  data-style="btn-primary" class="form-select">
                        <?php 
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?> 
                                    <option value="<?=$row['bid']?>"><?=$row["title"]?></option>
                                <?php
                            }
                        ?>
                    </select>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="files" class="form-label">ضمینه کردن عکس</label>
                        <input type="file" name="files" id="files" class="control">
                    <div class="mb-3 mt-3">
                    <input type="submit" value="اپلود" class="btn btn-primary">
                </form>
                    
            <?php
            }else {
                $query = "SELECT * FROM category;";
                $result = mysqli_query($db , $query);
                
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
                            <label for="title" class="form-label">سربرگ</label>
                            <input type="text" name="title" id="title" class="form-control">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="abstract" class="form-label">چکیده</label>
                            <input type="text" name="abstract" class="form-control" id="abstract">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="date_time" class="form-label">تاریخ</label>
                            <input type="date" name="date_time" id="date_time" class="form-control">
                        </div>
                    <?php
                    if ($result){
                        ?> 
                        <div class="mb-3 mt-3">
                        <label for="category"class="form-label"> انتخاب دسته بندی </label>
                        <select name="category" id="category" class="form-control">
                            <option value="00">خالی</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)){
                                ?> 
                                <option value="<?=$row['cid']?>"> <?=$row['c_name']?> </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                        <?php
                    }
                    ?> 
                        <div class="mb-3 mt-3">
                        <label for="category" class="form-label">ساختن دسته بندی جدید</label>
                        <input type="text" name="category" id="category" class="form-control">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="long_desc" class="form-label">توضیحات کامل</label>
                        <textarea name="long_desc" id="long_desc" class="form-control" cols="30" rows="40"></textarea>
                    </div>
                    <div class="mb-3 mt-3">
                        <input type="file" name="photo" id="photo" class="form-control">
                    </div>
                        <input type="submit" value="ساختن مقاله" class="btn btn-primary">
                </form>
                <?php
        }
        
        mysqli_close($db);
    }
    

    ?>
    </div>

</body>
</html>