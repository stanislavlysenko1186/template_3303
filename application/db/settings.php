<?php

define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "root");
define("MYSQL_DB", "template");

$db = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($db, 'utf8');

?>