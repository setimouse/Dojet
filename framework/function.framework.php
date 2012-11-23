<?php

function __dispatch($request) {
	global $config;
	foreach ( $config['dispatch'] as  $uri => $rule ) {
		
		if ( preg_match($uri, $request, $reg) ) {
			return array(	'logic_flow' => $rule[0], 
							'param' => $reg,
						);
		}
	}
	
	$defaultLogicName = $request.'.logic.php';
	if ( file_exists(LOGIC.$defaultLogicName) ) {
		return array(	'logic_flow' => LOGIC.$defaultLogicName,
						'param'	=> array(),
					);
	}
	
	return false;
}

function __autoload($className)
{
	require_once($className);
}
