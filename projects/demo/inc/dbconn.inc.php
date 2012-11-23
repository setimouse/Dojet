<?php
$__dbCfg = &$config['database'];

mysql_connect($__dbCfg['host'], $__dbCfg['username'], $__dbCfg['password']);

mysql_select_db($__dbCfg['dbname']);

mysql_query('SET NAMES UTF8');

unset($__dbCfg);
