<?php
require_once "./../checkLogin.php";
require_once "./../assets/php/autoload.php";

$Msg = new Msg();
$DB = new DB();

$id = $_GET['id'];

if(!is_numeric($id))
    exit("error id is not valid");

$stmt = $DB->prepare("DELETE FROM `users` WHERE id=:id");
$stmt->bindValue(":id", $id);
$result=$stmt->execute();
if(!$result)
{
    $Msg->error("عملیات حذف به خطا خورد -");
}
else
{
    $Msg->success("حذف موفقیت آمیز بود -");
}

header("location:users.php");
exit;