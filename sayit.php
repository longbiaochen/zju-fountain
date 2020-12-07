<?php
session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );

// constants
$PAGE_COUNT = 50;

$st = ' 欧尼酱，记得睡前40~60分钟喝杯水，可以帮助身体排泄垃圾，但是不要一口气喝太多，小心晚上漏水早上水肿哦，嘻嘻~Kiu~';
// $st = ' 感谢@撒利爱大象 , 成为人家的第40000个人类朋友，人家才不是最爱你呢 kiu~';

$is_debug = $_REQUEST['debug'];
$db=new DBManager();// wait for chun
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );
/*if($is_debug == 1){
    $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
}*/
$c->update(date('Y-m-d H:i:s').$st , $lat = 30.263101, $long = 120.12319 );

echo 'test done';

?>