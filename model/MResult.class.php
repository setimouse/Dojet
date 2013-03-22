<?php
class MResult {
    
    protected $result = array('error' => null, 'info' => null);
    
    const SUCCESS = 0;
    const FAIL = 1;
    
    public static function result($error, $info = null) {
        return new MResult($error, $info);
    }
    
    public function __construct($error, $info) {
        $this->error = $error;
        $this->info = $info;
    }
    
    public function __set($key, $value) {
        Dojet::assert(array_key_exists($key, $this->result));
        $this->result[$key] = $value;
    }
    
    public function __get($key) {
        Dojet::assert(array_key_exists($key, $this->result));
        return $this->result[$key];
    }
}