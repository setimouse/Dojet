<?php
abstract class _CacheObject
{
	private static $_cache;
	
	protected function getCache($key)
	{
		return $this->_cache[$key]['value'];
	}
	
	protected function setCache($key, $value, $readonly = false)
	{
		if ( isset($this->_cache[$key]) && $this->_cache[$key]['readonly'] )
		{
			return false;
		}
		
		$this->_cache[$key] = array(
			'value' => $value,
			'readonly' => $readonly,
		);
	}
}
