<?php

class DB extends PDO
{

    public function __construct($dbname = "javascripttrainingwebsite", $user = "root", $pass = "", $host = "localhost")
    {
        try {
            parent::__construct("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            //$this->exec("set names utf8mb4 collate utf8mb4_unicode_ci");
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);//تنظیم نوع پشفرض خروجی به آبجکت
        } catch (PDOException $e) {
            die('Database Error: ' . $e);
        }
    }

}