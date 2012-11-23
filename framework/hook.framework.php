<?php

$__g_hook = array();

function add_hook($hook_name, $hook_function, $priority = 10, $accepted_args = 0)
{
	global $__g_hook;
	
	if ( isset($__g_hook[$hook_name]) 
		&& !empty($__g_hook[$hook_name]) 
		&& !isset($__g_hook[$hook_name][$priority]) )
	{
		$need_ksort = true;
	}
	
	$__g_hook[$hook_name][$priority][$hook_function] = array('accepted' => $accepted_args);
	
	if ( $need_ksort )
	{
		krsort($__g_hook[$hook_name]);
	}
}

function remove_hook($hook_name, $hook_function)
{
	global $__g_hook;
	
	foreach ($__g_hook[$hook_name] as $priority => $hook)
	{
		if (isset($hook[$hook_function]))
		{
			unset($__g_hook[$hook_name][$priority][$hook_function]);
		}
	}
	
}

function remove_all_hooks($hook_name)
{
	global $__g_hook;
	
	if ( isset($__g_hook[$hook_name]) )
	{
		unset($__g_hook[$hook_name]);
	}
}

function invoke_hook($hook_name, $args = '')
{
	global $__g_hook;
	
	if ( null === ($hooks = $__g_hook[$hook_name]) || empty($hooks) )
	{
		return ;
	}
	
	$args = array();
	$func_num_args = func_num_args();
	if ( $func_num_args > 1 )
	{
		$args = array_slice(func_get_args(), 1, $func_num_args - 1);
	}
	
	foreach ($hooks as $priority => $hook_info)
	{
		foreach ($hook_info as $hook_function => $function_info)
		{
			$hook_args = array_slice($args, 0, $function_info['accepted']);
			call_user_func_array($hook_function, &$hook_args);
		}
	}
}

function hookable_call($function)
{
	$hook_args = array_slice(func_get_args(), 1, func_num_args() - 1);
	
	call_user_func_array('invoke_hook', array_merge(array('before_'.$function), $hook_args));
	
	call_user_func_array($function, $hook_args);
	
	call_user_func_array('invoke_hook', array_merge(array('after_'.$function), $hook_args));
}




































