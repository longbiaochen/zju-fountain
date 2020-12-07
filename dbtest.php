<?php

include_once ('tool.class.php');
include_once ('db.class.php');

//$dbm = new DBManager();
//$dbm->updateOntime('yes');
$tool = new Tool();
echo $tool->canSleep();

?>
