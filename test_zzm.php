<?php
session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );

/*$YX = 'yx';
$QS = 'qs';

$db=new DBManager();// wait for chun

$cur_user_name = 'slamzhzm';

$msg_data = date('Y-m-d H:i:s').' ';
$msg_yx = str_ireplace('{id}', '@'.$cur_user_name.' ', $db->getRandomMengYu($YX) );
$msg_qs = str_ireplace('{id}', '@'.$cur_user_name.' ', $db->getRandomMengYu($QS) );

echo '<br/> yx: '.$msg_data.$msg_yx;
echo '<br/> qs: '.$msg_data.$msg_qs;*/

$msg = '回复 :[哈哈]我‘’“”【】？；、还是，不打扰。。。你洗[兔子]澡了[尼玛]';
$msg2 = "回复   :无力";
$msg3 = "1234......adfdsfsadf你好么";
echo '<br/>'.$msg3;

// $ans = preg_replace("/\[([^]]+)]/i", "", $msg); 
// $ans = preg_replace('/\s/', '', $msg);


// $ans = preg_replace("/[[:punct:]]/",' ',$msg);
// $user_pattern = "/\@([\x{4e00}-\x{9fa5}|a-z|A-Z|_\-|0-9]+)/u";  
// $ans = preg_replace("/([\x{3002}\x{ff1b}\x{ff0c}\x{ff1a}\x{201c}\x{201d}\x{ff08}\x{ff09}\x{3001}\x{ff1f}\x{300a}\x{300b}])/u", ' ',$ans);
// $ans = str_ireplace('回复 :', '', $msg2);

//$ans = preg_replace('/\s/', '', $msg2);
//$ans = preg_replace('/回复\s*:/', '', $msg2);
//$ans = str_ireplace('回复:', '', $ans);

$ans = preg_replace("/[[:punct:]]/",' ',$msg3);
$ans = preg_replace('/([a-zA-Z]|\d)+/', '', $ans);

echo '<br/>'.$ans; 

?>