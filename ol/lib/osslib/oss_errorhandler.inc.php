<?php
   if (!defined('OSS_ERROR_HANDLER_TYPE')) define('OSS_ERROR_HANDLER_TYPE',E_USER_ERROR); 
   if (!defined('OSS_ERROR_HANDLER')) define('OSS_ERROR_HANDLER','OSS_Error_Handler');
   
   function OSS_Error_Handler($dbms, $fn, $eno, $msg, $p1, $p2 ) {
      if (error_reporting() == 0) return; 
      switch($fn) {
      case 'EXECUTE':
         $sql = $p1;
         $inputparams = $p2;
         $s = "$dbms error: [$eno: $msg] in $fn(\"$sql\")\n";
         break;
      case 'PCONNECT':
      case 'CONNECT':
         $host = $p1;
         $db = $p2;
         $s = "$dbms error: [$eno: $msg] in $fn($host, '****', '****', $db)\n";
         break;
      default:
         $s = "$dbms error: [$eno: $msg] in $fn($p1, $p2)\n";
         break;
      }
      if (defined('OSS_ERROR_LOG_TYPE')) {
         $t = date('Y-m-d H:i:s');
         if (defined('OSS_ERROR_LOG_DEST'))
            error_log("($t) $s", OSS_ERROR_LOG_TYPE, OSS_ERROR_LOG_DEST);
         else error_log("($t) $s", OSS_ERROR_LOG_TYPE);
      }
      trigger_error($s,OSS_ERROR_HANDLER_TYPE); 
   }
?>
