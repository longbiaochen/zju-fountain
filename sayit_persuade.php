<?php
session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );

// constants
$type = intval($_REQUEST['type']);

if( $type<0 || $type>6 ){
    echo '<br/>type error: '.$type;
    exit(0);
}

$array_msg = array(
    '米娜桑，早哦，昨天有没有好眠呢？嘻嘻~经过一夜的睡眠，我们的身体已经开始缺水了呢，所以记得起床的时候喝250CC的水哦，这样可以帮助我们的肾脏及肝脏解毒，清清爽爽的一天就要开始了呢，米娜桑，加油哦~',
    '欧尼酱，今天上班有没有堵车，有没有吃早餐呢？是不是又准备匆匆忙忙地喝杯咖啡就开始工作了呢？欧尼酱，你这样对自己很不好呢，一定要先喝点热水再喝咖啡哦~暖暖的一天要从暖暖的水杯开始哦~Kiu~',
    '11点啦，在暖气房呆了那么久，米娜桑赶紧起来小小运动下吧，喝杯热水，补充下流失的水分，放松下心情。上午的工作已经快要结束了呢，米娜桑，加油哦~Kiu~',
    '欧尼酱，中午有没有好好吃饭呢？有没有好好喝水呢？记得一定要饭后半个小时再喝水哦，不然胃宝宝会不舒服，消化功能会降低的，最爱你们啦~Kiu~',
    '米娜桑，是不是已经有点困困的了呢？喝杯热水代替午茶与咖啡吧，提神醒脑又健康，工作效率高又高，嘻嘻，Kiu~',
    '医生姐姐说，下班前喝杯水会增加饱腹感，待会吃晚餐时就不会暴饮暴食啦，这样身材会美美的，皮肤会美美的，心里更会暖暖的呢。米娜桑，快去喝杯热水吧~Kiu~',
    '欧尼酱，记得睡前40~60分钟喝杯水，可以帮助身体排泄垃圾，但是不要一口气喝太多，小心明天晒床单哦，嘻嘻~Kiu~',
);

$st = $array_msg[$type];

$is_debug = $_REQUEST['debug'];
$db=new DBManager();// wait for chun
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );
/*if($is_debug == 1){
    $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
}*/

$msg = date('Y-m-d H:i:s').' '.$st ;
$c->update($msg, $lat = 30.263101, $long = 120.12319 );

echo '<br/>'.$msg.' :test done';

?>
