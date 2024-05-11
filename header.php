<?php 
if (isset($_GET['logout'])){
    session_destroy();
    header("location:login.php");
}elseif(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $query = "SELECT profile_ ,username FROM users WHERE userid = $userid";
    $result = mysqli_fetch_assoc(mysqli_query($db , $query));
    $profile = $result['profile_'];
    $username = $result['username'];

}

?>

<header dir="ltr">
    <nav  class="d-flex align-item-c">
        <?php 
        if (isset($_SESSION['userid']) && !isset($_GET['logout'])){
            ?> 
            <img src="<?=$profile?>" alt="profile">
            <span class="username"><?=$username?></span>
            <?php
        }else {
            ?>
            <span class="while-color">WebLog E | L F</span>
            <?php 
        }
        ?>
        <ul class="ml-30x">
            <li><a href="/">خانه</a></li>
            <?php 
            if(isset($_SESSION['userid']) && isset($_SESSION['rol']) && $_SESSION['rol'] == 'writer'  && !isset($_GET['logout'])){
                ?> 
                <li><a href="writer.php">ساختن مقاله</a></li>
                <li><a href="writer.php?upload_media=true">اضافه کردن مدیا</a></li>
                <li><a href="profile.php?job=edit_profile">پروفایل</a></li>
                <li><a href="writer.php?job=feedbacks">نظرات کاربران</a></li>
                <li><a href="?logout=true">خروج</a></li>
                <?php
            }elseif(isset($_SESSION['userid']) && isset($_SESSION['rol']) && $_SESSION['rol'] == "admin"  && !isset($_GET['logout'])){
                ?> 
                <li><a href="profile.php?job=edit_profile">پروفایل</a></li>
                <li><a href="admin.php">پنل ادمین</a></li>
                <li><a href="?logout=true">خروج</a></li>
                <?php
            }elseif(isset($_SESSION['userid'])){
                ?> 
                    <li><a href="profile.php?job=like_list">مقاله های لایک شده</a></li>
                    <li><a href="profile.php?job=edit_profile">پروفایل</a></li>
                    <li><a href="?logout=true">خروج</a></li>
                <?php

            }else{
                ?> 
                    <li><a href="login.php">ورود</a></li>
                    <li><a href="register.php">ثبت نام</a></li>
                <?php
            }
            ?>
        </ul>
        <form action="index.php" method="post"  class="d-flex ml-30x" style="float:right;">
            <input type="text" name="search" class="input-search">
            <input type="submit" value="جستوجو" class="button-s">
        </form>
    </nav>
</header>