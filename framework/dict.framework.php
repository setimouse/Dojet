<?php

$__g_dict = array();

function D($strKey, $strValue = null, $boolReadonly = false) {
	if ( is_null($strValue) ) {
		return __getProperty($strKey);
	}
	return __setProperty($strKey, $strValue, $boolReadonly);
}

function __getProperty($key) {
	global $__g_dict;
	if ( !isset($__g_dict[$key]) ) {
		return null;
	}
	return $__g_dict[$key]['value'];
}

function __setProperty($key, $value, $readonly = false) {
	global $__g_dict;
	if ( isset($__g_dict[$key]) && $__g_dict[$key]['readonly'] ) {
		return false;
	}
	
	$__g_dict[$key]['value'] = $value;
	$__g_dict[$key]['readonly'] = $readonly;
	
	return true;
}
