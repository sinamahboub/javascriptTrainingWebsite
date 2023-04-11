<?php
require_once "./../checkLogin.php";
require_once "./../assets/php/autoload.php";

$Msg = new Msg();
$DB = new DB();

$valid = true;

if (isset($_POST['username'])) {
    $username = $_POST['username'];
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
}

if (isset($_POST['access'])) {
    $access = strip_tags($_POST['access']);
}

if ($username == "") {
    $Msg->error("لطفا نام کاربری را وارد کنید -");
    $valid = false;
}

if ($password == "") {
    $Msg->error("لطفا رمزعبور را وارد کنید -");
    $valid = false;
}

if ($email == "") {
    $Msg->error("لطفا ایمیل را وارد کنید -");
    $valid = false;
}

if ($access != 0 && $access != 1) {
    $Msg->error("لطفا نوع کاربری را وارد کنید -");
    $valid = false;
}

if ($valid == false) {
    header("location:users.php");
    exit();
}

$stmt = $DB->prepare("INSERT INTO `users`(`username`, `password`, `email`, `access`)
VALUES 
    (:username,:password,:email,:access)");
$stmt->bindValue(":username", $username);
$stmt->bindValue(":password", $password);
$stmt->bindValue(":email", $email);
$stmt->bindValue(":access", $access);

$result = $stmt->execute();
if (!$result) {
    $Msg->error("اطلاعات درج نشد -");
} else {
    $Msg->success("اطلاعات با موفقیت درج شد -");
}

header("location:users.php");
exit;