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


define('STATIC_ROOT',WEBROOT.'static/');

define('CONFIG',	PRJ.'config/');
define('WEBROOT',	PRJ.'webroot/');
define('LOG',		PRJ.'log/');
define('TEMPLATE',	PRJ.'template/');
define('INC',		PRJ.'inc/');
define('MODEL',		PRJ.'model/');
define('DATA',		PRJ.'data/');
define('LIB',       PRJ.'lib/');
define('UI',        PRJ.'ui/');
define('DAL',       PRJ.'dal/');

include(DLIB.'function.inc.php');

ini_set('include_path', implode(';',	array(
								ini_get('include_path'),
								FRAMEWORK,
								MODEL,
								DMODEL,
				)
			)
		);
		
ini_set('display_errors', 'On');


function __autoload($className)
{
    $include_path = array(
        UI,
        LIB,
        DAL,
        DLIB,
        MODEL,
        DMODEL,
        FRAMEWORK,
    );

    foreach ($include_path as $path) {
        $filename = $path.$className.'.class.php';
        if (file_exists($filename)) {
            require_once($filename);
            return ;
        }
    }

    trigger_error("class $className not found!\n");
}
