<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

echo $_SESSION['token']['access_token'];

?>