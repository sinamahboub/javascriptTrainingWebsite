<?php
require_once "./../checkLogin.php";
require_once "./../assets/php/autoload.php";
$Msg = new Msg();
$DB = new DB();

$stmt = $DB->prepare("SELECT * FROM `users` ORDER BY id ASC");
$result = $stmt->execute();
if (!$result) {
    exit("اشکال در پیدا کردن دیتا ها -");
}

$listUser = $stmt->fetchAll();


?>

<!doctype html>
<html lang="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>کاربران</title>
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<?php $Msg->show() ?>

<form action="addUpdateUser.php" method="post">

    <input type="hidden" name="id" id="" readonly>

    <input type="text" name="username" id="" placeholder="username">

    <input type="password" name="password" id="" placeholder="password">

    <input type="email" name="email" id="" placeholder="email">

    <select name="access">
        <option value="0" <?= (@$user->access == 0 ? "selected" : "") ?>>کاربر</option>
        <option value="1" <?= (@$user->access == 1 ? "selected" : "") ?>>ادمین</option>
    </select>

    <input type="submit" value="submit">

</form>

<table style="text-align: center">
    <thead>
    <tr>
        <th>ردیف</th>
        <th>نام کاربری</th>
        <th>ایمیل</th>
        <th>دسترسی</th>
        <th>حذف</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($listUser as $user) { ?>
        <tr>
            <td><?= $user->id ?></td>
            <td><?= $user->username ?></td>
            <td><?= $user->email ?></td>
            <td><?= $user->access ?></td>
            <td><a href="./deleteUser.php?id=<?= $user->id ?>">حذف</a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<script src="../assets/js/script.js"></script>
</body>
</html>