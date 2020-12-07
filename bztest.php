<?php
session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );

$db=new DBManager();// wait for chun
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );
$ms  = $c->home_timeline(); // done
$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

$msg=$_REQUEST['text'];

$type_state = $msg;
$ot = $db->getOntime();
$weibocnt = $db->getWeiboCnt();
$msg_random = '';
$cur_time = date('Y-m-d H:i:s');
if($type_state == 'on'){
    $msg_random = $cur_time.' '.$db->getRandomMengYu('on');
}
else if($type_state == 'off'){
    $msg_random = $cur_time.' '.$db->getRandomMengYu('off');
}
else if($type_state == 'sleep')
{
    $msg_random ='[兔子]http://aagqxg9w.developer.joyent.us/night/ 欧尼酱，我洗个澡就要休息了啊，门没锁，千万不要来偷窥啊，不过，多呼唤伦家几下还是会醒的nya~';
//    $db->updateOntime('sleep');
}
else if ($type_state == 'awake')
{
    $msg_random = '哼！人家才不是被你们这群死宅叫醒的呢！虽然，虽然在梦里有点期待的说...';
//    $db->updateOntime('awake');
}
echo "NOW STATE: " .$ot->state ."<br>";
echo "WEIBOCNT: " .$weibocnt ."<br>";
if( isset($_REQUEST['text']) ) {
    // $ret = $c->update( $_REQUEST['text'] );	//发送微博
    if ($type_state == 'awake')
    {
        $ret = $c->update($msg_random, $lat = 30.263101, $long = 120.12319 );
    }
    else if ($ot->state == 'awake')
    {
         if ($weibocnt == 0 || $weibocnt == 3 || $type_state == 'sleep')
        {
            $ret = $c->update($msg_random, $lat = 30.263101, $long = 120.12319 );
        }
      
        $db->updateWeiboCnt(($weibocnt + 1) % 6);
    }
    if ($type_state == 'sleep' || $type_state == 'awake')
        $db->updateOntime($type_state);	
	if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
		echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
	} else {
		echo "<p>发送成功</p>";
		
		// check sleep
		if($type_state == 'sleep' || $type_state == 'awake'){
            exit(0);
		}
		
		// sf hero, add by zzm
		$msg_id = $ret['id'];
		$sfhero = new SfHero();
		$sfhero->id = $msg_id;
		$sfhero->state = $type_state;
		$sfhero->status = 'false';
		$db->updateSfHero($sfhero);
	}
} else {
  echo 'Error: message is empty';
}

?>
