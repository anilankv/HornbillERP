<?php

if ( !defined('IN_OSS') )
{
   die("Hacking attempt ");
}

define('DEBUG', 1); // Debugging off

define('DELETED', -1);
define('ANONYMOUS', -1);

define('USER', 0);
define('ADMIN', 1);
define('MOD', 2);

define('BEGIN_TRANSACTION', 1);
define('END_TRANSACTION', 2);

define('GENERAL_MESSAGE', 200);
define('GENERAL_ERROR', 202);
define('CRITICAL_MESSAGE', 203);
define('CRITICAL_ERROR', 204);

define('SESSION_METHOD_COOKIE', 100);
define('SESSION_METHOD_GET', 101);

?>
