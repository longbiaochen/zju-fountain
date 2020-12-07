<?php

include_once ('db.class.php');
include_once ('config.php');
include_once ('saetv2.ex.class.php');
include_once ('util.php');

$db = new DBManager();
$tkn = $db -> getToken();
// since id to avoid duplication
$since_id = $db -> getSinceId();

$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $tkn);
$ms = $c -> mentions(1, 200, $since_id, 0, 0, 0, 1);
$statuses = $ms['statuses'];

// store mentions to database
foreach (array_reverse($statuses) as $item) {
    // weibo id
    $weibo_id = $item['id'];
    // user screen name
    $name = $item['user']['screen_name'];
    // weibo text
    $text = $item['text'];
    
   // if (substr_count($text, "@浙大CCNT实验室饮水机") > 0)
   // {
        $text = str_replace("@浙大CCNT实验室饮水机"," ", $text);
        echo 'Storing: id=' . $weibo_id . ' name=' . $name . ' text=' . $text . '<br/>';
        $ami = new Ami();
        $ami -> weiboid = $weibo_id;
        $ami -> name = $name;
        $ami -> text = $text;
   // $db -> ami_add_question($weibo_id, $name, $text);
        $db -> addAmi($ami);
 //   }
    $since_id = $weibo_id;
    $db -> setSinceId($since_id);

}

// update since_id

?>
