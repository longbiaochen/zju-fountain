<?php

include_once('db.class.php');
include_once('config.php');
include_once('saetv2.ex.class.php');
include_once('util.php');
include_once('tool.class.php');

$db = new DBManager();
$tool = new Tool();
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );

$ami = $db->getAnAmi();
echo $ami->weiboid ."getAnAmi<br>";
$reply_ype = 0;

if ($ami == null) {
    $ami = $db->getAnAmiPl();
    echo $ami->weiboid ."getAnAmiPl<br>";
    $reply_ype = 1;
    
    if ($ami == null) {
        exit(0);
    }
}

$ans = $tool->getAnswer($ami->text) ." @亜美AmI";
//$ans = substr($ans, 0, $len - 4);// str_replace("\r", "[兔子]", $ans);
echo "##".$ans ."##";
echo '<br>mid:'.$ami->mid.' weiboid:'.$ami->weiboid;

$ret = null;
if($reply_ype == 0){
    $ret = $c->send_comment($ami->weiboid, $ans);
}
else{
    $ret = $c->reply($ami->mid, $ans, $ami->weiboid);
}

if($reply_ype == 0){
    $db->deleteAmi($ami->id);
}
else{
    $db->deleteAmiPl($ami->id);
}
echo '<br>delete '.$ami->id;

if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
		echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
} else {
    echo "<p>发送成功</p>";
}

?>

