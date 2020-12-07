<?php
include_once ('db.class.php');

class Util {
	function __construct() {

	}

	public function nextBoiled() {
		// prev_time+interval-now
		$db = new DBManager();
		$sfhero = $db -> getSfHero();
		if ($sfhero == null) {
			echo "no object.";
		}

		$pvs_time = $sfhero -> created_at;
		echo $pvs_time . '</br>';
		// $pvs_time='2011-12-28 20:49:19';
		$now = date('Y-m-d H:i:s');
		echo $now . '</br>';
		$interval = $db -> getInterval();
		$ret = $this -> dateDiff('n', $pvs_time, $now);
		$ret = $interval-$ret;
		// echo "diff=" . $ret;
		return $ret;

	}

    function hasKeyword($text) 
    {
        if (substr_count($text, '多久') > 0)
            return 1;
        return 0;

    }   

	// 以下代码来自 最佳TV，春哥超赞 [兔子]
	function transformDate($date) {
		$sp = explode(" ", $date);
		$ret = $sp[5] . "-" . $this -> getMonth($sp[1]) . "-" . $sp[2] . " " . $sp[3];
		return $ret;
	}

	function getMonth($str) {
		if ($str == 'Jan')
			return 1;
		if ($str == 'Feb')
			return 2;
		if ($str == 'Mar')
			return 3;
		if ($str == 'Apr')
			return 4;
		if ($str == 'May')
			return 5;
		if ($str == 'Jun')
			return 6;
		if ($str == 'Jul')
			return 7;
		if ($str == 'Aug')
			return 8;
		if ($str == 'Sep')
			return 9;
		if ($str == 'Oct')
			return 10;
		if ($str == 'Nov')
			return 11;
		if ($str == 'Dec')
			return 12;
		return 0;
	}

	function dateDiff($interval, $date1, $date2) {
		$retval = 0;
		$d1 = strtotime($date1);
		$d2 = strtotime($date2);
		$time_difference = $d2 - $d1;
		switch ($interval) {
			case "w" :
				$retval = bcdiv($time_difference, 604800);
				break;
			case "d" :
				$retval = bcdiv($time_difference, 86400);
				break;
			case "h" :
				$retval = bcdiv($time_difference, 3600);
				break;
			case "n" :
				$retval = bcdiv($time_difference, 60);
				break;
			case "s" :
				$retval = $time_difference;
				break;
		}
		return $retval;
	}

}
?>
