<?php
require "./assets/php/autoload.php";
$Msg = new Msg();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<?php $Msg->show() ?>

<form action="./auth.php" method="post">

    <input type="text" name="username" id="" placeholder="username">

    <input type="password" name="password" id="" placeholder="password">

    <input type="submit" value="submit">

</form>

<script src="./assets/js/script.js"></script>

</body>
</html>