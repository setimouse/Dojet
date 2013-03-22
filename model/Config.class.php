<?php
class Config {
    /**
     * keypath is a string divided by '.'
     *
     * @param string $keyPath
     * @return mix
     */    
    public static function configForKeyPath($keyPath) {
        $key = strtok($keyPath, '.');
        $value = $GLOBALS['config'];
        while ($key && $value) {
            $value = $value[$key];
            $key = strtok('.');
        }
        return $value;
    }
    
    public static function &configRefForKeyPath($keyPath) {
        $key = strtok($keyPath, '.');
        $value = &$GLOBALS['config'];
        while ($key) {
            if (!is_array($value)) {
                $value = array();
            }
            
            if (!key_exists($key, $value)) {
                $value[$key] = null;
            }
            $value = &$value[$key];
            $key = strtok('.');
        }
        return $value;
    }
}
