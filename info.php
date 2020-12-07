<?php
/*
    created by zzm
    Statisticalinformation of posts by 浙大CCNT实验室饮水机
*/

session_start();

include_once( 'config.php' );
include_once( 'db.class.php' );
include_once( 'saetv2.ex.class.php' );

// constants
$PAGE_COUNT = 50;

// arrays
$array_text = array();
$array_retweat = array();
$array_comment = array();
$array_count = array();
$array_type = array();
$array_avg_retweat = array();
$array_avg_comment = array();

$is_debug = $_REQUEST['debug'];
$db=new DBManager();// wait for chun
$tkn = $db->getToken();
$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $tkn/*$_SESSION['token']['access_token']*/ );
/*if($is_debug == 1){
    $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
}*/
$cs = $c->user_timeline_by_name('浙大CCNT实验室饮水机');

/*
    type_req = 0: 显示所有微博
    type_req = 1: 只显示“自动发送”的微博
    type_req = 2: 只显示“人工发送”的微博
*/
$type_req=$_REQUEST['type'];

$total_number = $cs['total_number'];
$total_page_number = (int)($total_number/$PAGE_COUNT)+1;

$page_number = 1;
while($page_number <= $total_page_number){
    if($page_number > 1){
        $cs  = $c->user_timeline_by_name('浙大CCNT实验室饮水机', $page_number);
    }
    $statuses = $cs['statuses'];
    
    foreach($statuses as $item){ 
        $text = ' ';
        $first_char = substr($item['text'], 0, 1);
        $type = 0;
        if($first_char == '2'){
            $text = substr($item['text'], 19);
        }
        else {
            $type = 1;
            $text = $item['text'];
        }
        
        $retweat_count = $item['reposts_count'];
        $comments_count = $item['comments_count'];
        
        $idx = contain_text_idx($array_text, $text);
        
        if($idx == -1) {
            array_push($array_text, $text);
            array_push($array_retweat, $retweat_count);
            array_push($array_comment, $comments_count);
            array_push($array_count, 1);
            if($type == 0){
                array_push($array_type, '自动发送');
            }
            else {
                array_push($array_type, '人工发送');
            }
        }
        else {
            $array_retweat[$idx] += $retweat_count;
            $array_comment[$idx] += $comments_count;
            $array_count[$idx] += 1;
        }
    }
    
    $page_number++;
}

//////////////////////////////////////////////////////////////////////////////////////////
$count = count($array_text);
for($idx1=0; $idx1<$count; $idx1++){
    $avg1 = $array_retweat[$idx1]/$array_count[$idx1];
    array_push($array_avg_retweat, $avg1);
}

for($idx2=0; $idx2<$count; $idx2++){
    $avg2= $array_comment[$idx2]/$array_count[$idx2];
    array_push($array_avg_comment, $avg2);
}
//////////////////////////////////////////////////////////////////////////////////////////   

//set_avg($array_text, $array_retweat, $array_count, $array_avg_retweat);
//set_avg($array_text, $array_comment, $array_count, $array_avg_comment);

sort_by_retweat($array_text, $array_retweat, $array_comment, $array_count, $array_avg_retweat, $array_avg_comment, $array_type);

print_info($array_text, $array_retweat, $array_comment, $array_count, $array_avg_retweat, $array_avg_comment, $array_type, $type_req);


//////////////////////////////////////////////////////////////////////////////////////////
///functions

function print_info(&$array_text, &$array_retweat, &$array_comment, &$array_count, &$array_avg_retweat, &$array_avg_comment, $array_type, $type){
    $count = count($array_text);
    $ii = 0;
    
    if($type == 1){
        echo '<br/># 只显示“自动发送”的微博 #';
    }
    else if($type == 2){
        echo '<br/># 只显示“人工发送”的微博 #';
    }
    else{
        echo '<br/># 显示所有微博 #';
    }
    echo '<br/>';
    
    for($idx=0; $idx<$count; $idx++){
        if($type == 1){
            if($array_type[$idx] == '人工发送'){
                continue;
            }
        }
        $ii++;
        echo 
            '<br/>'.$ii.
            '<br/>平均转发数：'.$array_avg_retweat[$idx].
            '<br/>平均评论次数：'.$array_avg_comment[$idx].
            '<br/> 类型：'.$array_type[$idx].
            '<br/> 数量：'.$array_count[$idx].
            '<br/>'.$array_text[$idx].
            '<br/>================================================================================<br/><br/>';
    }
}

function sort_by_retweat(&$array_text, &$array_retweat, &$array_comment, &$array_count, &$array_avg_retweat, &$array_avg_comment, &$array_type){
    $total_count = count($array_text);
    
    for($idx=0; $idx<$total_count; $idx++){
        for($i=$idx+1; $i<$total_count; $i++){
            if($array_avg_retweat[$i] > $array_avg_retweat[$idx]){
                exchange($idx, $i, $array_avg_retweat);
                
                exchange($idx, $i, $array_text);
                exchange($idx, $i, $array_count);
                exchange($idx, $i, $array_type);
                exchange($idx, $i, $array_retweat);
                exchange($idx, $i, $array_comment);
                exchange($idx, $i, $array_avg_comment);
            }
        }
    }
}

function exchange($i, $j, &$array){
    $tmp = $array[$i];
    $array[$i] = $array[$j];
    $array[$j] = $tmp;
}

function set_avg(&$array_text, &$array_x, &$array_count, &$array_y){
    $count = count($array_text);
    for($idx=0; $idx<$count; $idx++){
        echo $array_x[$idx]/$array_count[$idx].'<br/>';
        array_push($array_y, $array_x[$idx]/$array_count[$idx]);
    }
}

function contain_text_idx(&$array_text, $cur_text){
    $count = count($array_text);
    for($idx=0; $idx<$count; $idx++){
        if($cur_text == $array_text[$idx]){
            return $idx;
        }
    }
    
    return -1;
}

?>
