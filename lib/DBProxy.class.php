<?php
require_once(CONFIG.'database.conf.php');

class DBProxy 
{
	private static $queryNum = 0;
	private static $conn = null;
	
	public static function connect() 
	{
		if (!is_resource(self::$conn))
		{
    		$dbConfig = $GLOBALS['config']['database'];
    		self::$conn = @mysql_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password']);
    		@mysql_select_db($dbConfig['dbname']);
		}
		
		return self::$conn;
	}
	
	public static function close()
	{
		@mysql_close(self::$conn);
		self::$conn = null;
	}
	
	protected static function _doQuery($strSql)
	{
		self::$queryNum++;
		
		self::connect();
		
		$ret = mysql_query($strSql);
		
		return $ret;
	}
	
	public static function doInsert($strSql)
	{
		return self::_doQuery($strSql);
	}
	
	public static function doUpdate($strSql)
	{
		return self::_doQuery($strSql);
	}
	
	public static function doDelete($strSql)
	{
		return self::_doQuery($strSql);
	}
	
	public static function doSelect($strSql)
	{
		return self::_doQuery($strSql);
	}
	
	public static function rs2array($strSql)
	{
		$rs = self::doSelect($strSql);
		$ret = array();
		if ( false === $rs ) 
		{
			return $ret;
		}
	
		if ( $rs ) 
		{
			while ( $row = @mysql_fetch_assoc($rs) ) 
			{
				$ret[] = $row;
			}
		}
		return  $ret;
	}
	
	public static function rs2rowline($strSql) 
	{
		$rs = self::doSelect($strSql);
		$ret = @mysql_fetch_assoc($rs);
		return  $ret;
	}
	
	public static function rs2rowcount($strSql) 
	{
		$ret = self::rs2firstvalue($strSql);
		return $ret;
	}
	
	public static function rs2firstvalue($strSql) 
	{
		$row = self::rs2rowline($strSql);
		$ret = $row[0];
		return $ret;
	}
	
	public static function rs2foundrows() 
	{
		return self::rs2firstvalue("SELECT FOUND_ROWS()");
	}
	
	public static function realEscapeString($string) 
	{
		return mysql_real_escape_string($string);
	}
	
	public static function getQueryNum()
	{
		return self::$queryNum;
	}
}











