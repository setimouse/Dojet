<?php
class HomeAction extends BaseAction {
    
    public function execute() {
        $this->assign('text', 'It works!');
        $this->display('index.tpl.php');
        fastcgi_finish_request();
    }
    
}