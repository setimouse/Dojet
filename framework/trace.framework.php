<?php
define('DEBUG',	0x00000001);
define('NOTICE',0x00000010);
define('WARN',	0x00000100);
define('ERROR', 0x00001000);

define('TRACE_OFF',	0x0);

class Trace
{
	private static $instance;
	
	/**
	 * @return Trace
	 */
	protected function getInstance()
	{
		if (!(self::$instance instanceof Trace))
		{
			self::$instance = new Trace();
		}
		
		return self::$instance;
	}
	
	public static function debug($msg)
	{
		$traceObj = self::getInstance();
		$traceObj->_trace($msg, DEBUG);
	}
	
	public static function notice($msg)
	{
		$traceObj = self::getInstance();
		$traceObj->_trace($msg, NOTICE);
	}
	
	public static function warn($msg)
	{
		$traceObj = self::getInstance();
		$traceObj->_trace($msg, WARN);
	}
	
	public static function fatal($msg)
	{
		$traceObj = self::getInstance();
		$traceObj->_trace($msg, ERROR);
	}
	
	protected function _trace($msg, $level = DEBUG, $file = '', $line = '') 
	{
		if ( !in_array($level, array(TRACE_ALL, DEBUG, ERROR, NOTICE, TRACE_OFF, WARN)) 
			|| 0 === ($level & $GLOBALS['config']['global']['traceLevel']) ) 
		{
			return;
		}
		
		if ( is_array($msg) || is_object($msg) ) 
		{
			$msg = print_r($msg, true);
		}
		
		$pid = self::getpid();
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$trace = "[".date("y-m-d H:i:s")."][$pid][$ip] $msg";
		
		if ( !empty($file) ) 
		{
			$trace.= "\t[$file]";
		}
		
		if ( !empty($line) ) 
		{
			$trace.= "\t[$line]";
		}
		
		$trace.= "\n";
		$tracefile = self::get_logfile($level);
		
		if ( $fp = @fopen(LOG.$tracefile, 'a') ) 
		{
			@fwrite($fp, $trace);
			@fclose($fp);
		}
	}

	protected function getpid()
	{
		if ( 'WIN' === substr(PHP_OS, 0, 3) ) 
		{
			return 0;
		}
	
	    return posix_getpid();
	}
	
	protected function get_logfile($level)
	{
		$strLogfile = date("Ymd", D('nowtime'));
		switch ($level) 
		{
			case DEBUG:
				$strLogfile = 'debug.'.$strLogfile;
				break;
			case ERROR:
				$strLogfile = 'error.'.$strLogfile;
				break;
			case WARN:
				$strLogfile = 'warn.'.$strLogfile;
				break;
			case NOTICE:
				$strLogfile = 'notice.'.$strLogfile;
				break;
		}
		$strLogfile.= '.log';
		return $strLogfile;
	}
}

