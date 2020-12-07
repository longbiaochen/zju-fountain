<?php

include_once('db.class.php');
include_once('config.php');
include_once('saetv2.ex.class.php');

$db = new DBManager();
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );
$c->send_comment(3395636169203881, '这是一条很萌的测试。');

?>

