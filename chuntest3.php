<?php

include_once( 'db.class.php' );

$db = new DBManager();
$ami = $db->getAnAmi();
$post_data = array();
$txt =$ami->text;// mb_convert_encoding($ami->text, "UTF-8", "GBK");
$post_data['text'] = $txt;
echo $txt ."<br>";
$url = "http://124.160.148.2/ami/qa";
$o="";
foreach ($post_data as $k=>$v)
{
    $o.= "$k=".urlencode($v)."&";
}

$post_data=substr($o,0,-1);
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$result = curl_exec($ch);
echo $result;

?>
