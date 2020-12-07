<?php

include_once ('db.class.php');

class Tool
{
    function canSleep()
    {
        $db = new DBManager();
        $ot = $db->getOntime();
        $tm1 = $ot->time;
        $tm2 = date("Y-m-d H:i:s");
        $itv = $this->dateDiff('n', $tm1, $tm2);
        if ($itv < 5)
        {
            //$db->updateOntime('yes');
            return 1;
        }
        else
        {
            //$db->updateOntime('no');
            return 0;
        }
    }

    function dateDiff($interval, $date1, $date2)
    {  
        $retval = 0;
        $d1 = strtotime($date1);
        $d2 = strtotime($date2);
        $time_difference   =   $d2 - $d1;  
        switch ($interval)
        { 
            case "w": $retval = bcdiv($time_difference, 604800); break;  
            case "d": $retval = bcdiv($time_difference, 86400);  break;  
            case "h": $retval = bcdiv($time_difference, 3600);   break;  
            case "n": $retval = bcdiv($time_difference, 60);     break;  
            case "s": $retval = $time_difference;                break;  
        }  
        return   $retval;
    }

    function getAnswer($text)
    {
        $post_data = array();
        $post_data['text'] = $text;
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
        //为了支持cookie
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        return $result;
    } 
}

?>
