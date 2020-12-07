<?php

include_once('db.class.php');
include_once('config.php');
include_once('saetv2.ex.class.php');
include_once('util.php');

$db = new DBManager();
$tkn = $db->getToken();
$lid = $db->getLid();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );
$ms = $c->mentions(1, 200, $lid, 0, 0,0, 1);
$statuses = $ms['statuses'];
$sh = $db->getSfHero();
$util = new Util();
$items = array();
$llid = $lid;
$ot = $db->getOntime();

if ($ot->state == 'sleep')
    exit(0);
foreach(array_reverse($statuses) as $item)
{

    if ($util->hasKeyword($item['text']) == 1 )
    {
        array_push($items, $item);
       // echo $item['text'] ."<br>";
    }
    $llid = $item['id'];
//    foreach($item as $k => $v)
//        echo $k .":".$v ."<br>";
//    echo "<br>";

}
$cur_time = date('Y-m-d H:i:s');
if (count($items) == 0)
{
    $db->updateLid($llid);
    exit(0);
}
else 
    $ridx = mt_rand(0, count($items) - 1);
$s = $items[$ridx];
$at = "";
for ($i = 0; $i < count($items); $i ++)
{
    if ($i != $ridx && $i < 6)
    {
        $item = $items[$i];
        $user = $item['user'];
        $sn = $user['screen_name'];
        $at = " ". $at ."@".$sn;
    }
}
echo $s['text'];
$itv = $util->nextBoiled();
if ($sh->state == 'on')
    $sen = "现在水是开着的哦 " .$at;
else
    $sen = '咕噜咕噜，还有'.$itv .'分钟水就可以开了哦~ ' .$at;
$ret = $c->send_comment($s['id'], $sen);
if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
		echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
	} else {
		echo "<p>发送成功</p>";
        $db->updateLid($llid);
    }
//echo $ridx ."<br>";
//echo $items[$ridx]['text'];

?>
