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

$db=new DBManager();
$sfhero = $db->getSfHero();

if($sfhero == null){
    echo "sfhero = null <br/>";
    exit(0);
}
else{
    $cur_id = $sfhero->id;
    $cur_state = $sfhero->state;
    $cur_status = $sfhero->status;
    
    // debug
    //$cur_id = '3394107345355580';
    //$cur_state = 'on';
    //$cur_status = 'false';
    
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
            post_sfhero($comments, $c, $db, $cur_id, $cur_state);
        }
        else {
            $ccs  = $c->get_comments_by_sid($cur_id, $page_number);
            if( is_array($ccs) ){
                $comments = $ccs['comments'];
                post_sfhero($comments, $c, $db, $cur_id, $cur_state);
            }
        }
    }
}
else if($cur_status == "true"){
    // this weibo is sfed...
}

function post_sfhero($comments, $cc, $dbb, $id, $state){
    $bd_count = 0;

    foreach( array_reverse($comments) as $item ) {
        $cur_user_name = $item['user']['screen_name'];
        /*if( $state == 'on' ){
            $ret = $cc->update(date('Y-m-d H:i:s').' 好稀饭哦，伦家的换水英雄@'.$cur_user_name.' ~~，快喝热水啦 kiu~ ', $lat = 30.263101, $long = 120.12319 );
        }
        else{
            $ret = $cc->update(date('Y-m-d H:i:s').' 不理你了，倒水禽兽@'.$cur_user_name.' ~~，讨厌，水倒光了 nya~ ', $lat = 30.263101, $long = 120.12319 );
        }*/
        $bd_count++;
        if($bd_count == 2){
            $ret = $cc->update(date('Y-m-d H:i:s').' momo@'.$cur_user_name.' ~~，板凳也不要气馁哦[兔子] kiu~ ', $lat = 30.263101, $long = 120.12319 );
            // update sfhero db
            $new_hero = new SfHero();
            $new_hero->id = $id;
            $new_hero->state = $state;
            $new_hero->status = "true";
            
            $tmp_sfhero = $dbb->getSfHero();
            if( $tmp_sfhero != null && $tmp_sfhero->id == $new_hero->id){
                $dbb->updateSfHero($new_hero);
            }
            
            // debug
            echo "<br/>text: ".$item['text'];
            echo "<br/>user name: ".$cur_user_name ;
            
            break;
        }
        else if($bd_count == 1){
            continue;
        }
    }
}

?>