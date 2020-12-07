<?php
session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );

$db=new DBManager();
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn);
$trends = $c->mentions();
print_r($trends);

?>