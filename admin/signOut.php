<?php session_start();
session_destroy();
//setcookie("nameUser",null,time()-(24*60*60),"/");
header("location:formLogin.php");
exit;