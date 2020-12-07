<?php
include_once( 'db.class.php' );
$dbm = new DBManager();

$text = $_POST["text"];
$state = $_POST["state"];

$dbm->addMengyu($text, $state);
header("Location: http://zjufountain.sinaapp.com/addMengyu.html");
?>
