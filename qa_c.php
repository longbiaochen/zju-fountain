<?php
// QA from comments of last post
// ------------------------------

session_start();

include_once ('config.php');
include_once ('db.class.php');
include_once ('util.php');
include_once ('saetv2.ex.class.php');

$db = new DBManager();
$tkn = $db -> getToken();

$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $tkn/*$_SESSION['token']['access_token']*/
);

$sfhero = $db -> getSfHero();

if ($sfhero == null) {
	echo "sfhero = null <br/>";
	exit(0);
}

$cur_id = $sfhero -> id;
$cur_state = $sfhero -> state;
$cur_status = $sfhero -> status;
// check if this weibo has been replied
$cid = $db -> getCid();
if ($cid == $cur_id) {
	echo "cid repeat! cid = " . $cid;
	exit(0);
}

// get comments of the selected weibo
$ccs = $c -> get_comments_by_sid($cur_id);
if (is_array($ccs)) {
	// compose message
	$u = new Util();
	$msg = '';
	if ($cur_state == 'on') {
		// currently on
		$msg = 'è®¨åŽŒï¼Œæ°´æ—©å°±å¼€äº†å•¦ï¼Œå�ˆæ�¥è°ƒæˆ�ä¼¦å®¶~';

	} else {
		$time = $u -> nextBoiled();
		$msg = 'å’•å™œå’•å™œï¼Œè¿˜æœ‰' . $time . 'åˆ†é’Ÿå°±æŠŠæ°´çƒ§å¼€äº†å“¦~ ';

	}
	echo "msg=" . $msg . '<br/>';

	// determine people to be replied
	$keyword = $db -> getKeyword();
	echo "keyword=" . $keyword . '<br/>';

	// determine users to be replied
	$comments = $ccs['comments'];
	$cnt = 0;
	$list = '';
	foreach ($comments as $item) {
		$cur_user_name = $item['user']['screen_name'];
		$text = $item['text'];
		// echo $text . '<br/>';
		if (substr_count($text, $keyword) > 0 && $cnt < 2) {
			$cnt++;
			echo $cnt . ': ' . $cur_user_name . '<br/>---------</br>';
			$list .= ' @' . $cur_user_name;

		}

	}
	if ($cnt > 0) {
		// post weibo
		$weibo = $msg . $list;
		echo "weibo=" . $weibo . '<br/>';

		$ret = $c -> send_comment($cur_id, $weibo);
		if (isset($ret['error_code']) && $ret['error_code'] > 0) {
			echo "<p>å�‘é€�å¤±è´¥ï¼Œé”™è¯¯ï¼š{$ret['error_code']}:{$ret['error']}</p>";
		} else {
			echo "<p>å�‘é€�æˆ�åŠŸ</p>";
		}

		$db -> updateCid($cur_id);

	} else {

	}

}
?>
