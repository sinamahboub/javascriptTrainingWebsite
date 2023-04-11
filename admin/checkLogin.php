<?php session_start();
if(empty($_SESSION['user_id']))
{
	header("location:formLogin.php");
	exit;
}