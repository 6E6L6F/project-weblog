<?php 
function show_users($db , $msg=null) {
    $query_get_users_list = "SELECT userid,username,first_name,last_name,gmail,profile_ FROM users WHERE rol = 'user';";
    $result = mysqli_query($db , $query_get_users_list);
    ?>
    <form action="?job=delete_user" method="POST">
        <?php 
        if (!empty($msg)) {
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
                    <th>نام کاربری</th>
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
                            <input type="checkbox" class="form-check-input" name="delete[]" value="<?=$row['userid']?>">
                        </td>
                    </div>
                    <td class="form-check-label" ><?=$row['username']?></td>
                    <td class="form-check-label" ><?=$row['first_name']?></td>
                    <td class="form-check-label" ><?=$row['last_name']?></td>
                    <td class="form-check-label" ><?=$row['gmail']?></td>
                    <td class="form-check-label" ><img src="<?=$row['profile_']?>" class="img-style" alt="profile_photo"></td>
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

function execute_query($query , $db) {
    $result = mysqli_query($db ,$query);
    ?>
        <?php
    while ($row = mysqli_fetch_assoc($result)){
        ?> 
        <div class="col-md-3 col-lg-2 col-sm-6">
            <div class="card">
                <div class="card-body container">
                    
                    <h3 class="card-title"><?=$row["title"]?></h2>
                    <a href="blogs.php?id=<?=$row["bid"]?>"> 
                    <div class="img-index">
                       <img src="<?=$row["photo"]?>" class="image card-img-top"> 
                    </div>
                    <div class="overlay">
                        <p class="card-text"><?=$row["abstract"]?></p>
                        <span class="desc">تاریخ انتشار <?=$row['date_time']?> </span> <br>
                        <span class="desc">بازدید : <?=$row['seen']?></span><br>
                        <span class="desc">دسته بندی : <?=$row['c_name']?></span>
                      </div>
                    <br>       
                    </a>              
                </div>
                <a href="blogs.php?id=<?=$row["bid"]?>" class="btn btn-outline-dark">بازدید</a>
            </div>
        </div>
    <?php
    }
}
?>

<?php 
function show_report($db , $msg){
    $query_get_report = "SELECT feedback.report,feedback.bid , feedback.fid ,users.first_name,users.last_name,users.username FROM feedback INNER JOIN users ON users.userid = feedback.userid WHERE feedback.report != '';";
    $result = mysqli_query($db , $query_get_report);
    ?> 

    <form action="?job=delete_report" method="post">
        <?php 
        if (!empty($msg)) {
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
                <th>نام </th>
                <th>نام خانوادگی </th>
                <th>نام کاربری</th>
                <th>مقاله</th>
                <th>متن گزارش</th>
            </thead>
            <tbody>
                <?php 
                    while ($row = mysqli_fetch_assoc($result)){
                        ?> 
                        <tr>
                            <div class="form-check">
                                <td>
                                    <input type="checkbox" name="fid[]" value="<?=$row['fid']?>" class="form-check-input">
                                </td>
                            </div>
                            <td class="form-check-label" ><?=$row["first_name"]?></td>
                            <td class="form-check-label" ><?=$row["last_name"]?></td>
                            <td class="form-check-label" ><?=$row["username"]?></td>
                            <td class="form-check-label" ><a href="blogs.php?id=<?=$row['bid']?>">بازدید</a></td>
                            <td class="form-check-label" ><?=$row["report"]?></td>
                            
                        </tr>
                        <?php
                    }           
                ?>
            </tbody>
        </table>
        <input type="submit" value="حذف گزارش" class="btn btn-primary">
    </form>
    <?php
}

function show_feedbacks($db , $wid){
    $query_get_feedbacks = "SELECT feedback.comment , feedback.bid , blogs.title , blogs.seen , users.first_name , users.last_name ,users.username ,SUM(feedback.like_) AS likes FROM blogs INNER JOIN feedback ON feedback.bid = blogs.bid  INNER JOIN users ON feedback.userid = users.userid  WHERE blogs.wid = $wid GROUP BY feedback.comment;";
    $result = mysqli_query($db , $query_get_feedbacks);
    ?> 
        <table class="table table-striped" border="1">
            <thead>
                <th>نام </th>
                <th>نام خانوادگی </th>
                <th>نام کاربری</th>
                <th>مقاله</th>
                <th>نظر کاربر</th>
                <th>تعداد بازدید</th>
                <th>لایک شده</th>

            </thead>
            <tbody>
                <?php 
                    while ($row = mysqli_fetch_assoc($result)){
                        ?> 
                        <tr>
                            <td class="form-check-label" ><?=$row["first_name"]?></td>
                            <td class="form-check-label" ><?=$row["last_name"]?></td>
                            <td class="form-check-label" ><?=$row["username"]?></td>
                            <td class="form-check-label" ><a href="blogs.php?id=<?=$row['bid']?>"><?=$row['title']?></a></td>
                            <td class="form-check-label" ><?=$row["comment"]?></td>
                            <td class="form-check-label" ><?=$row["seen"]?></td>
                            <td class="form-check-label" ><?php
                            if ($row["likes"] == 1){
                                echo "✅";
                            }else {
                                echo "❌";
                            }
                            ?></td>
                        </tr>
                        <?php
                    }           
                ?>
            </tbody>
        </table>
    <?php
}



?>