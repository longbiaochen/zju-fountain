<?php

include_once( 'db.class.php' );

$db = new DBManager();
$ami = new Ami();
$ami -> weiboid = 12321312312321;
$ami -> name = 'hello';
$ami -> text = '你好';
$db -> addAmi($ami);
?>
