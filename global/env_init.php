<?php
define('DGLOBAL', dirname(__FILE__).'/');

define('DOJET', realpath(DGLOBAL.'..').'/');
define('FRAMEWORK', DOJET.'framework/');

define('DUTIL',		DOJET.'util/');
define('DPROJECTS',	DOJET.'projects/');
define('DLOG',		DOJET.'log/');
define('DLIB',		DOJET.'lib/');
define('DGLOBAL',	DOJET.'global/');
define('DMODEL',	DOJET.'model/');


define('CONFIG',	PRJ.'config/');
define('WEBROOT',	PRJ.'webroot/');
define('STATIC_ROOT',WEBROOT.'static/');
define('LOGIC',		PRJ.'logic/');
define('LOG',		PRJ.'log/');
define('TEMPLATE',	PRJ.'template/');
define('INC',		PRJ.'inc/');
define('MODEL',		PRJ.'model/');
define('DATA',		PRJ.'data/');

ini_set('include_path', implode(';',	array(
								ini_get('include_path'),
								MODEL,
								DMODEL,
				)
			)
		);
		




