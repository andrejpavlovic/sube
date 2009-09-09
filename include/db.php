<?php
// Some crappy mySQL wrapper class from Cosmin Cristea <kokosanu at yahoo dot com>

require 'include/SafeSQL.class.php';

class SQLLayer {
	var $server;
	var $user;
	var $passwd;
	var $dbname;
	var $link;
	
	function SQLLayer ($server = "", $user = "", $pass = "", $dbname = "") {
		$this->server = $server;
		$this->user = $user;
		$this->passwd = $pass;
		$this->dbname = $dbname;

		$this->connect();
	}
	
	function connect() {
    $err_level = ini_get('error_reporting');
    error_reporting(0);

    if (!$this->link = mysql_connect ($this->server, $this->user, $this->passwd))
      die('We are sorry, but our database it down. Please come back in a few hours.');

    error_reporting($err_level);

    mysql_select_db ($this->dbname, $this->link);

    $this->safesql = new SafeSQL_MySQL($this->link);
	}
	
	function close() {
		return (mysql_close($this->link));	
	}
	
	function query($query) {
		$this->rs = mysql_query ($query, $this->link);
		if (!$this->rs)
		{
			echo("Error: Invalid query \"".$query."\"...<br>");
			echo("Error: ".mysql_errno()."<br>");
			die("Error: ".mysql_error()."<br>");
		} else {
			return ($this->rs);
		}
	}
	
	function data_seek($row = "") {
		if ($row == "")
			$row = 0;
			
		mysql_data_seek ($rs, $row);
        return true;
	}
	
	function fetch_array($rs = "") {
		if ($rs == "")
			$rs = $this->rs;
		
		$this->row = mysql_fetch_array($rs, MYSQL_BOTH);
        return ($this->row);
	}
	
	function num_rows($rs = "") {
		if ($rs == "")
			$rs = $this->rs;
        return (mysql_num_rows($rs));
	}
	
	function affected_rows() {
		return mysql_affected_rows($this->link);
	}
	
	function free_result($rs = "") {
		if ($rs == "")
			$rs = $this->rs;
        
        return (mysql_free_result($rs));
	}

  function insert_id() {
    return mysql_insert_id($this->link);
  }

  function safesql($query_string, $array) {
    return $this->safesql->query($query_string, $array);
  }
}
?>
