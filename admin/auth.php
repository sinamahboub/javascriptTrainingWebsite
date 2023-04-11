<?php session_start();
require_once "./assets/php/autoload.php";

$DB = new DB();
$Msg = new Msg();
$Func = new Func();

$username = $Func->faNumToEn(strtolower(strip_tags($_POST['username'])));// REZA => reza
$password = $Func->faNumToEn($_POST['password']);


$DB = new DB();
$Msg = new Msg();

$stmt = $DB->prepare("SELECT * FROM `users` WHERE username=:username");
$stmt->bindValue(":username", $username);
$stmt->execute();
$user = $stmt->fetch();

if (isset($user->id)) {
    if ($user->password == $password) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['access'] = $user->access;
        $Msg->success(" خوش آمدید");
        header("location:index.php");
        exit;
    } else {
        $Msg->error("رمز عبور اشتباه است");
        header("location:formLogin.php");
        exit;
    }

} else {
    $Msg->error("نام کاربری یافت نشد");
    header("location:formLogin.php");
    exit;
}

//print_r($user);