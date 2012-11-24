<?php

class Event 
{
	protected static $_eventList;
	
	private static $_need_sort = false;
	
	public static function doWhen($event, $funcName, $priority = 0)
	{
		$eventList = &self::$_eventList[$event];
		
		$prority = intval($prority);
		
		if ( isset($eventList['func_index'][$funcName]) )
		{
			$oldPriority = $eventList['func_index'][$funcName];
			if ( $priority === $oldPriority )
			{
				return ;
			}

			unset($eventList['priority_index'][$oldPriority][$funcName]);
		}
		
		if ( !isset($eventList['priority_index'][$priority]) )
		{
			self::$_need_sort = true;
		}
		
		$eventList['priority_index'][$priority][$funcName] = '';
		$eventList['func_index'][$funcName] = $prority;
	}
	
	public static function happened($event/*, args = '' */)
	{
		$args = func_get_args();
		array_shift($args);
		
		$eventList = self::$_eventList[$event];
		
		if ( self::$_need_sort )
		{
			ksort($eventList['priority_index']);
			self::$_need_sort = false;
		}
		
		foreach ( (array)$eventList['priority_index'] as $priority => $keyFuncNames )
		{
			foreach ( (array)$keyFuncNames as $funcName => $bar )
			{
				call_user_func_array($funcName, $args);
			}
		}
		
		return $args;
	}
}


$s = "1a";

function makeint($i, $j)
{
	print $i.' '.$j;
}

BingoEvent::doWhen('shouldInt', 'makeint');

$result = BingoEvent::happened('shouldInt', 1, 2);

print_r($result);




























