<?php

class MRequest {

    private static $_params;
    
    public static function get($key) {
        return $_GET[$key];
    }
    
    public static function post($key) {
        return $_POST[$key];
    }
    
    public static function request($key) {
        return $_REQUEST[$key];
    }
    
    public static function cookie($key) {
        return $_COOKIE[$key];
    }
    
    public static function setParam($key, $value) {
        self::$_params[$key] = $value;
    }
    
    public static function getParam($key) {
        return self::$_params[$key];
    }
    
    public static function getAllParams() {
        return self::$_params;
    }
}