<?php

class Dojet {
    
    public function start() {
        $requestUri = substr($_SERVER['REQUEST_URI'], 1);
        $dispatcher = SingletonFactory::getInstance('Dispatcher');
        $dispatcher->dispatch($requestUri);
    }
    
    public function assert($condition, $message = null, $file = null, $line = null) {

        if ($condition) {
            return;
        }

        $assertMessage = '';

        if (null !== $message) {
            $assertMessage.= $message;
        }
        if (null !== $file) {
            $assertMessage.= ' ['.$file.']';
        }
        if (null !== $line) {
            $assertMessage.= ' ['.$line.']';
        }

        die($message);
    }
}