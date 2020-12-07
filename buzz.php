<?php
session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );
include_once( 'util.php' );

$db=new DBManager();// wait for chun
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );

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
    $msg_random = $cur_time.' '.$db->getRandomMengYu('sleep');
}
else if ($type_state == 'awake')
{
    $msg_random = $cur_time.' '.$db->getRandomMengYu('awake');
}
echo "NOW STATE: " .$ot->state ."<br>";
echo "WEIBOCNT: " .$weibocnt ."<br>";
if( isset($_REQUEST['text']) ) {
    if ($type_state == 'awake')
    {
        $ret = $c->update($msg_random, $lat = 30.263101, $long = 120.12319 );
    }
    else if ($ot->state == 'awake')
    {
        // if ($weibocnt == 0 || $weibocnt == 3 || $type_state == 'sleep')
      //  {
            $ret = $c->update($msg_random, $lat = 30.263101, $long = 120.12319 );
      //  }
      
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
            echo 'sleep/awake state, no need to sf hero';
            exit(0);
		}
		
		// sf hero, add by zzm
		$msg_id = $ret['id'];
		$msg_created_at = $ret['created_at'];
		
		if( !is_numeric($msg_id) || $msg_id == '' || $msg_id == null){
            echo '$msg_id is null or not a number';
            exit(0);
		}
		
		$util = new Util();
		
		$sfhero = new SfHero();
		$sfhero->id = $msg_id;
		$sfhero->state = $type_state;
		$sfhero->status = 'false';
		$sfhero->created_at = $util->transformDate($msg_created_at);
		$db->updateSfHero($sfhero);
	}
} else {
  echo 'Error: message is empty';
}

?>
