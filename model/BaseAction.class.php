<?php

abstract class BaseAction {
    
    private $_tplData;
    
    public function assign($key, $value) {
        $this->_tplData[$key] = $value;
    }
    
    public function display($template) {
        $tplData = $this->_tplData;
        include(TEMPLATE.$template);
    }
    
    public abstract function execute();
    
}