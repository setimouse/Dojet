<?php
define('PRJ', realpath(dirname(__FILE__).'/../').'/');

include(PRJ.'/../../global/env_init.php');

$dojet = SingletonFactory::getInstance('Dojet');

$dojet->start();