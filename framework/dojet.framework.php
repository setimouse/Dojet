<?php
include(dirname(__FILE__).'/../global/env_init.php');

require(FRAMEWORK.'function.framework.php');
require(FRAMEWORK.'hook.framework.php');
require(FRAMEWORK.'dict.framework.php');
require(FRAMEWORK.'trace.framework.php');

include(DGLOBAL.'global_init.php');

@include(CONFIG.'global.conf.php');
@include(CONFIG.'database.conf.php');
@include(CONFIG.'dispatch.conf.php');

@include(INC.'common.inc.php');
@include(INC.'function.inc.php');

include(DLIB.'function.inc.php');


Trace::notice(sprintf("[%s] %s\t%s", $_SERVER['REQUEST_URI'], $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']));

$__request = substr($_SERVER['REQUEST_URI'], 1);
$__action = __dispatch($__request);

if ( false === $__action ) {
	header("Not Found", null, 404);
	die();
}

$param = $__action['param'];

include($__action['logic_flow']);
