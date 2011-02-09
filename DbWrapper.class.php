<?php
class DbWrapper
{
	private $connection = null;
	
	private static $instance = null;
	
	private function __construct()
	{
		$this->connection = mysql_connect('localhost', 'root', '');
		mysql_select_db('test', $this->connection);
	}
	
	public function query($sql)
	{
		$result = mysql_query($sql, $this->connection);
		return new DbResultWrapper($result);
	}
	
	public function escape($string)
	{
		return mysql_real_escape_string($string, $this->connection);
	}
	
	public function quote($string)
	{
		if (is_null($string))
		{
			return 'NULL';
		}
		else if ($string === false)
		{
			return 0;
		}
		else if ($string === true)
		{
			return 1;
		}
		else
		{
			return '"' . $this->escape($string) . '"';
		}
		
	}
	
	public function selectDb($newDb)
	{
		return mysql_select_db($newDb, $this->connection);
	}
	
	public function getInsertId()
	{
		return mysql_insert_id($this->connection);
	}
	
	public static function getInstance($forceNew = false)
	{
		if (is_null(self::$instance) || $forceNew)
		{
			self::$instance = new DbWrapper();
		}
		return self::$instance;
	}
	
	public function getError()
	{
		return mysql_error($this->connection);
	}
	
}

class DbResultWrapper
{	
	private $result = null;
	public function __construct($result)
	{
		$this->result = $result;
	}
	
	public function getRow()
	{
		if (($this->result))
		{
			return mysql_fetch_assoc($this->result);
		}
		return false;
	}
}