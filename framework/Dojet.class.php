<?php

class Dojet {
    
    public function start() {
        $requestUri = substr($_SERVER['REQUEST_URI'], 1);
        $dispatcher = SingletonFactory::getInstance('Dispatcher');
        $dispatcher->dispatch($requestUri);
    }
    
}