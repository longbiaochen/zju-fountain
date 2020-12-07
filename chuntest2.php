<?php

include_once( 'db.class.php' );

$db = new DBManager();
$ami = $db -> getAnAmi();
echo $ami->weiboid ."<br>";
echo $ami->name ."<br>";
echo $ami->text ."<br>";
$db->deleteAmi($ami->id);
?>

