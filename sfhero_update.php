<?php
session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );
include_once( 'util.php' );

$type_state=$_REQUEST['type'];

$is_debug = $_REQUEST['debug'];
$db=new DBManager();// wait for chun
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );
/*if($is_debug == 1){
    $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
}*/
$cs = $c->user_timeline_by_name('浙大CCNT实验室饮水机');

$msg_id = -1;
$msg_created_at = '';
$statuses = $cs['statuses'];
foreach($statuses as $item){
    $msg_id = $item['mid'];
    $msg_created_at = $item['created_at'];
    break;
}

if( is_numeric($msg_id) && $msg_id!=-1){
    $util = new Util();
    $sfhero = new SfHero();
    $sfhero->id = $msg_id;
    $sfhero->state = $type_state;
    $sfhero->status = 'false';
    $sfhero->created_at = $util->transformDate($msg_created_at);
    $db->updateSfHero($sfhero);
    
    echo 'update successfully';
}


?>