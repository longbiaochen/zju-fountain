<?php

include_once ('db.class.php');
include_once ('config.php');
include_once ('saetv2.ex.class.php');
include_once ('util.php');

$db = new DBManager();
$tkn = $db -> getToken();
// since id to avoid duplication
$since_id = $db -> getCSinceId();

$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $tkn);
$ms = $c -> comments_to_me(1, 200, $since_id, 0, 0, 0);
//$statuses = $ms['statuses'];
$comments = $ms['comments'];

// store mentions to database
foreach (array_reverse($comments) as $item) {
    // weibo id
    $weibo_id = $item['id'];
    
    // mid of this comment, add by jerremy
    $mid = $item['status']['mid'];
    
    // user screen name
    $name = $item['user']['screen_name'];
    // weibo text
    $text = $item['text'];
    
    // replace motion as [兔子], add by jerremy
    $text = preg_replace("/\[([^]]+)]/i", "", $text); 
    $text = preg_replace('/回复.*:/', '', $text);
    if(0 == strlen($text)){
        echo '<br/> emotions only: '.$text; 
        continue;
    }
    
    // check if no word, add by jerremy
    if(0 == isNoWord($text)){
        echo '<br/> no word: '.$text; 
        continue;
    }
    
   // if (substr_count($text, "@浙大CCNT实验室饮水机") > 0)
   // {
        $text = str_replace("@浙大CCNT实验室饮水机"," ", $text);
        echo 'Storing: id=' . $weibo_id . ' mid=' . $mid . ' name=' . $name . ' text=' . $text . '<br/>';
        $ami = new Ami();
        $ami -> weiboid = $weibo_id;
        $ami -> mid = $mid;
        $ami -> name = $name;
        $ami -> text = $text;
   // $db -> ami_add_question($weibo_id, $name, $text);
        $db -> addAmiPl($ami);
 //   }
    $since_id = $weibo_id;
    $db -> setCSinceId($since_id);

}

function isNoWord($str){
    $tmp = preg_replace("/[[:punct:]]/", "",$str);
    $tmp = preg_replace("/([\x{3002}\x{ff1b}\x{ff0c}\x{ff1a}\x{201c}\x{201d}\x{ff08}\x{ff09}\x{3001}\x{ff1f}\x{300a}\x{300b}])/u", "",$tmp);
    $tmp = preg_replace('/([a-zA-Z]|\d)+/', '', $tmp);
    
    if(0 == strlen($tmp)){
        return 0;
    }
    return 1;
}

// update since_id

?>
