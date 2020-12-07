<?php

class DBManager {
	public $mysql;

	function __construct() {
		$this -> mysql = new SaeMysql();
	}

	function addMengyu($my, $state) {
		$table = '';
		if ($state == 'on')
			$table = 'onmy';
		else if ($state == 'off')
			$table = 'offmy';
		else if ($state == 'sleep')
			$table = 'sleepmy';
		else if ($state == 'awake')
			$table = 'awakemy';
		else if ($state == 'yx')
			$table = 'yxmy';
		else if ($state == 'qs')
			$table = 'qsmy';
		$sql = "INSERT INTO `" . $table . "`(`text`) VALUES('" . $my . "')";
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
	}

	function getRandomMengYu($state) {
		$table = '';
		if ($state == 'on')
			$table = 'onmy';
		else if ($state == 'off')
			$table = 'offmy';
		else if ($state == 'sleep')
			$table = 'sleepmy';
		else if ($state == 'awake')
			$table = 'awakemy';
		else if ($state == 'yx')
			$table = 'yxmy';
		else if ($state == 'qs')
			$table = 'qsmy';
		$sql = "SELECT * FROM `" . $table . "` ORDER BY RAND() LIMIT 1";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$txt = $data[0]['text'];
			return $txt;
		}
		return null;
	}

	function updateSfHero($sh) {
		$this -> deleteSfHero();
		$this -> addSfHero($sh);
	}

	function deleteSfHero() {
		$sql = "DELETE FROM sfhero";
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
	}

	function addSfHero($sh) {
		$sql = "INSERT INTO `sfhero`(`id`, `state`, `status`, `created_at`) VALUES(" . $sh -> id . ",'" . $sh -> state . "','" . $sh -> status . "','". $sh->created_at ."')";
		echo $sql;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
	}

	function getSfHero() {
		$sql = "SELECT * FROM `sfhero`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$sh = new SfHero();
			$sh -> id = $data[0]['id'];
			$sh -> state = $data[0]['state'];
            $sh -> status = $data[0]['status'];
            $sh->created_at = $data[0]['created_at'];
			return $sh;
		}
		return null;
	}

	function updateOntime($sleep, $weiboid = 0) {
		if ($weiboid == 0)
			$sql = "UPDATE `ontime` SET time = current_timestamp(), state = '" . $sleep . "'";
		else
			$sql = "UPDATE `ontime` SET time = current_timestamp(), state = '" . $sleep . "', weiboid=" . $weiboid;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
	}

	function getOntime() {
		$sql = "select * from `ontime`";
		$data = $this -> mysql -> getdata($sql);
		if (count($data) > 0) {
			$ot = new Ontime();
			$ot -> time = $data[0]['time'];
			$ot -> state = $data[0]['state'];
			$ot -> weiboid = $data[0]['weiboid'];
			return $ot;
		}
		return null;
	}

	function getWeiboCnt() {
		$sql = "SELECT * FROM `weibocnt`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$cnt = $data[0]['cnt'];
			return $cnt;
		}
		return 0;
	}

	function updateWeiboCnt($cnt) {
		$sql = "UPDATE `weibocnt` SET cnt = " . $cnt;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
	}

	function getToken() {
		$sql = "SELECT * FROM `weibodata`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$tkn = $data[0]['token'];
			return $tkn;
		}
		return '0';
	}

	function updateToken($tkn) {
		$sql = "UPDATE `weibodata` SET token = '" . $tkn . "'";
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
	}

	// Felix
	function getKeyword() {
		$sql = "SELECT * FROM `qa`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$keyword = $data[0]['word'];
			return $keyword;
		}
		return '饮水机娘';
	}

	function getInterval() {
		$sql = "SELECT * FROM `qa`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$interval = $data[0]['interval'];
			return $interval;
		}
		return '5';
    }

    function getLid()
    {
        $sql = "SELECT * FROM `weibocnt`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$lid = $data[0]['lid'];
			return $lid;
		}
		return 0;
    }
    function updateLid($lid)
    {
        $sql = "UPDATE `weibocnt` SET lid = " . $lid;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
    }
    function getCid()
    {
        $sql = "SELECT * FROM `weibocnt`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$lid = $data[0]['cid'];
			return $lid;
		}
		return 0;
    }
    function updateCid($cid)
    {
        $sql = "UPDATE `weibocnt` SET cid = " . $cid;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
    }
    function addAmi($ami)
    {
     //   if ($ami -> name == null || $ami -> text == null)
        //        return;
        $ami->text = str_replace('\'',' ', $ami->text);
        $ami->name = str_replace('\'',' ', $ami->name);
        $sql = "INSERT INTO `ami`(`weiboid`, `name`, `text`) VALUES(" . $ami -> weiboid . ",'" . $ami -> name . "','" . $ami -> text . "')";
		echo $sql;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
    }
    function getAnAmi()
    {
        $sql = "SELECT * FROM `ami` ORDER BY id";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
            $ami = new Ami();
            $ami -> id = $data[0]['id'];
			$ami -> weiboid = $data[0]['weiboid'];
			$ami -> name = $data[0]['name'];
            $ami -> text = $data[0]['text'];
			return $ami;
		}
		return null;
    }
    function deleteAmi($id)
    {
        $sql = "DELETE FROM `ami` WHERE `id` = " .$id;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
    }
    function addAmiPl($ami)
    {
     //   if ($ami -> name == null || $ami -> text == null)
        //        return;
        $ami->text = str_replace('\'',' ', $ami->text);
        $ami->name = str_replace('\'',' ', $ami->name);
        $sql = "INSERT INTO `ami_pl`(`weiboid`, `mid` ,`name`, `text`) VALUES(" . $ami -> weiboid . "," .$ami -> mid .",'" . $ami -> name . "','" . $ami -> text . "')";
		echo $sql;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
    }
    function getAnAmiPl()
    {
        $sql = "SELECT * FROM `ami_pl` ORDER BY id";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
            $ami = new Ami();
            $ami -> id = $data[0]['id'];
            $ami -> weiboid = $data[0]['weiboid'];
            $ami -> mid = $data[0]['mid'];
			$ami -> name = $data[0]['name'];
            $ami -> text = $data[0]['text'];
			return $ami;
		}
		return null;
    }
    function deleteAmiPl($id)
    {
        $sql = "DELETE FROM `ami_pl` WHERE `id` = " .$id;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
    }
    function getSinceId()
    {
        $sql = "SELECT * FROM `since`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$lid = $data[0]['sid'];
			return $lid;
		}
		return 0;
    }
    function setSinceId($sid)
    {
        $sql = "UPDATE `since` SET sid = " . $sid;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
    }
    function getCSinceId()
    {
        $sql = "SELECT * FROM `since`";
		$data = $this -> mysql -> getData($sql);
		if (count($data) > 0) {
			$lid = $data[0]['csid'];
			return $lid;
		}
		return 0;
    }
    function setCSinceId($csid)
    {
        $sql = "UPDATE `since` SET csid = " . $csid;
		$this -> mysql -> runSql($sql);
		if ($this -> mysql -> errno() != 0) {
			die("Error:" . $this -> mysql -> errmsg());
		}
    }
    function close()
    {
        $this->mysql->closeDb();
    }
}

class SfHero {
	public $id;
	public $state;
    public $status;
    public $created_at;
}

class Ontime {
	public $time;
	public $state;
	public $weiboid;
}
class Ami {
    public $id;
    public $weiboid;
    public $mid;
    public $name;
    public $text;
}
?>
