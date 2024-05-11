<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assete/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Register Form</title>
</head>
<body dir="rtl">
    <?php 
    
    include("config.php");
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $db = mysqli_connect(hostname:$information['HOST'] ,username:$information['USERNAME'],password:$information['PASSWORD'],database:$information['DATABASE'] );
        if (!$db) {
            echo "connections database fiald..!";
            exit(0);
        }
        $username = $_POST['username'];
        $query_check_username = "SELECT username FROM users WHERE username = '$username';";
        $result = mysqli_fetch_assoc(mysqli_query($db , $query_check_username));
        if ($result) {
            $msg = "this username is not valid";
        }else {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];        
            $password = $_POST['password'];
            $gmail = $_POST['gmail'];
            $tmp_photo = $_FILES['profile']['tmp_name'];
            $rol = $_POST["rol"];
            $profile_path = "photo/profile/" . $_FILES['profile']['name'];
            $query_check_username = "SELECT username FROM users WHERE username = '$username';";
            $result = mysqli_fetch_assoc(mysqli_query($db , $query_check_username));
            move_uploaded_file($tmp_photo , $profile_path);
            $passwd = hash('sha384', $password);
            $query = "INSERT INTO users(first_name , last_name , username , passwd , gmail , rol , profile_) VALUES('$first_name' , '$last_name' , '$username' , '$passwd' , '$gmail' , '$rol' , '$profile_path');";
            mysqli_query($db , $query);
            mysqli_close($db);
            header("location:login.php");
        }
    }
    ?>


    <div class="from-style">
        <form action="" method="post" enctype="multipart/form-data">
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
                <label for="first_name" class="form-label">نام:</label>
                <input type="text" class="form-control" id="first_name" placeholder="نام خود را وارد کنید" name="first_name">
            </div>
            <div class="mb-3 mt-3">
                <label for="last_name" class="form-label">نام خانوادگی:</label>
                <input type="text" class="form-control" id="last_name" placeholder="نام خانوادگی حود را وارد کنید" name="last_name">
            </div>
            
            <div class="mb-3 mt-3">
                <label for="Username" class="form-label">نام کاربری:</label>
                <input type="text" class="form-control" id="Username" placeholder="نام گاربری خود را وارد کنید" name="username">
            </div>

            <div class="mb-3 mt-3">
                <label for="email" class="form-label">جیمیل:</label>
                <input type="email" class="form-control" id="gmail" placeholder="ایمیل خود را وارد کنید" name="gmail">
            </div>
           
           <div class="mb-3 mt-3">
                <select name="rol" id="rol"  data-style="btn-primary" class="form-select">
                    <option value="writer">نویسنده</option>
                    <option value="user">کاربر</option>
                    <option value="admin">admin</option>
                </select>
            </div>
           
           <div class="mb-3 mt-3">
                <label for="profile" class="form-label">عکس پروفایل:</label>
                <input class="form-control" type="file" id="profile" name="profile"/>
           </div>
           
            <div class="mb-3">
                <label for="password" class="form-label">پسورد:</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">ثبت نام</button>
        </form>
        </div> 

</body>
</html>