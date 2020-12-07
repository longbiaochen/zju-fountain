<?php
session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );

// constants
$PAGE_COUNT = 50;

$is_debug = $_REQUEST['debug'];
$db=new DBManager();// wait for chun
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );
/*if($is_debug == 1){
    $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
}*/

// debug 
// $cur_id = '3393935752274545';
// $cur_state = 'on';
// $cur_status = 'false';

$sfhero = $db->getSfHero();

if($sfhero == null){
    echo "sfhero = null <br/>";
    exit(0);
}
else{
    $cur_id = $sfhero->id;
    $cur_state = $sfhero->state;
    $cur_status = $sfhero->status;
    
    echo "id: ".$cur_id;
    echo "<br/>state: ".$cur_state;
    echo "<br/>status: ".$cur_status;
}

// check status
if($cur_status == "false"){
    $ccs  = $c->get_comments_by_sid($cur_id);
    
    if( is_array($ccs) ){
        $comments = $ccs['comments'];
        
        $total_number = $ccs['total_number'];
        $page_number = (int)($total_number/$PAGE_COUNT)+1;
        
        if($page_number == 1){
            reply_sfhero($comments, $c, $db, $cur_id, $cur_state);
        }
        else {
            $ccs  = $c->get_comments_by_sid($cur_id, $page_number);
            if( is_array($ccs) ){
                $comments = $ccs['comments'];
                reply_sfhero($comments, $c, $db, $cur_id, $cur_state);
            }
        }
    }
}
else if($cur_status == "true"){
    echo '<br/>'.date('Y-m-d H:i:s').' this comment has been sfed...';
}

function reply_sfhero($comments, $cc, $dbb, $id, $state){
    $YX = 'yx';
    $QS = 'qs';
    
    if( !is_array($comments) ){
        echo '<br>$comments not array, maybe token out of time';
        exit(0);
    }

    foreach( array_reverse($comments) as $item ) {
        $cur_user_name = $item['user']['screen_name'];
        $cid = $item['id'];
        $msg_data = date('Y-m-d H:i:s').' ';
        if( $state == 'on' ){
            $msg = str_ireplace('{id}', '@'.$cur_user_name.' ', $dbb->getRandomMengYu($YX));
            $ret = $cc->reply($id, $msg, $cid);
        }
        else{
            $msg = str_ireplace('{id}', '@'.$cur_user_name.' ', $dbb->getRandomMengYu($QS));
            $ret = $cc->reply($id, $msg, $cid);
        }
        
        // update sfhero db
        $tmp_sfhero = $dbb->getSfHero();
        $new_hero = new SfHero();
        $new_hero->id = $id;
        $new_hero->state = $state;
        $new_hero->status = "true";
        $new_hero->created_at = $tmp_sfhero->created_at;
        
        if( $tmp_sfhero != null && $tmp_sfhero->id == $new_hero->id){
            $dbb->updateSfHero($new_hero);
        }
        
        // debug
        echo "<br/>text: ".$item['text'];
        echo "<br/>user name: ".$cur_user_name ;
        
        break;
    }
}

?>