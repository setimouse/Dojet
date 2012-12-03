<?php
require_once(CONFIG.'dispatch.conf.php');

class Dispatcher {
    
    public static function dispatch($requestUri) {
        $routes = $GLOBALS['config']['dispatch'];
        
        if (!is_array($routes)) {
        	return ;
        }
        
        foreach ($routes as $routeRegx => $actionInfo) {
    		if ( preg_match($routeRegx, $requestUri, $reg) ) {
    		    for ($i = 1; $i < count($reg); $i++) {
                    RequestParam::setParam($i - 1, $reg[$i]);
    		    }
    		    
				$actionName = $actionInfo['action'];

    		    $action = new $actionName();
    		    
    		    assert($action instanceof BaseAction);
    		    
    		    $action->execute();
    		    
    		    break ;
    		}
        }
    }
    
}

