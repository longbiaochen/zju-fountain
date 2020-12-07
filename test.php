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
if ($sh->state == 'on')
    exit(0);
foreach(array_reverse($statuses) as $item)
{

    if ($util->hasKeyword($item['text']) == 1 )
    {
        array_push($items, $item);
        echo $item['text'] ."<br>";
    }
    $llid = $item['id'];
//    foreach($item as $k => $v)
//        echo $k .":".$v ."<br>";
//    echo "<br>";

}
if (count($items) == 0)
    $ridx = 0;
else 
    $ridx = mt_rand(0, count($items) - 1);
$s = $items[$ridx];
$itv = $util->nextBoiled();
$sen = '咕噜咕噜，还有'.$itv .'分钟水就可以开了哦~';
$c->send_comment($s['id'], $sen);
$db->updateLid($llid);
//echo $ridx ."<br>";
//echo $items[$ridx]['text'];

?>
