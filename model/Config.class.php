<?php

class Config {
    
    /**
     * 配置项字典
     *
     * @var array
     */
    private $_config;
    
    private $_instance;
    
    public function __construct() {
        self::$_config = array();
    }
    
    public static function getInstance() {
        if (null === self::_instance) {
        	self::$_instance = new Config();
        }
        return self::$_instance;
    }
    
    /**
     * 通过配置项的key获取value
     *
     * @param string $key
     * @return array
     */
    public function getValueForKey($key) {
        return self::$_config[$key];
    }
    
    /**
     * 通过keypath获取value
     * keypath是以'.'分割的字符串
     *
     * @param string $keypath
     * @return mix
     */
    public function getValueForKeypath($keypath) {
        $key = strtok($keypath, '.');
        $value = self::$_config;
        while ($key && $value) {
        	$value = $value[$key];
        	$key = strtok('.');
        }
        return $value;
    }
    
    protected function _setValueForKeyAtNode($node, $key, $value = null) {
        assert(false === strpos($key), 'key must NOT contain "."');
        if (null === $node) {
        	$node = array();
        }
        if ($value) {
            $node[$key] = $value;
        }
    }
    
    /**
     * 通过key设置value
     *
     * @param string $key
     * @param mix $value
     */
    public function setValueForKey($key, $value) {
        $this->_setValueForKeyAtNode(self::$_config, $key, $value);
    }
    
    /**
     * 通过keypath设置value
     *
     * @param string $keypath
     * @param mix $value
     */
    public function setValueForKeypath($keypath, $value) {
        $key = strtok($keypath, '.');
        $node = &self::$_config;
        while ($key) {
            $this->_setValueForKeyAtNode($node, $key);
        	$node = &$node[$key];
        	$key = strtok('.');
        }
        $node = $value;
    }
}