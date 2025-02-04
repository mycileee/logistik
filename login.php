<?php 
require 'function.php';
// $conn = mysqli_connect("localhost", "root", "topabis", "login");

// cek login
if(isset($_POST['login'])){
    $user = $_POST['user'];
    $password = $_POST['password'];

    // validasi database
    $cekdatabase = mysqli_query($conn,"SELECT * FROM login where users='$user' and password='$password'");
    // hitung jumlah data
    $hitung = mysqli_num_rows($cekdatabase);

    if($hitung>0){
        $_SESSION['log'] = 'True';
        header('location:index.php');
    } else {
        header('location:login.php');
    };
};

if(!isset($_SESSION['log'])){

} else {
    header('location:index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/img/icon.png">
    <link rel="stylesheet" href="/css/login.css">
    <title>Login | Logistik</title>
</head>
<body>
 <form method="POST"> 
<div class="wrapper">
    <div class="login_box">
        <div class="login-header">
            <span>Login</span>
        </div>
        <div class="input_box">
            <input type="text" name="user" id="user" class="input-field" required>
            <label for="user" class="label">Username</label>
        </div>
        <div class="input_box">
            <input type="password" name="password" id="password" class="input-field" required>
            <label for="password" class="label">Password</label>
        </div>
        <div class="input_box">
            <button type="submit" name="login" class="input-submit" value="Login">Submit </button>
        </div>
    </div>
</div>
</form>  
</body>
</html>