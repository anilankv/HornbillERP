<?php
   if (!defined('OSS_ERROR_HANDLER_TYPE')) define('OSS_ERROR_HANDLER_TYPE',E_USER_ERROR); 
   define('OSS_ERROR_HANDLER','oss_throw');
   
   class OSS_Exception extends Exception {
      var $dbms;
      var $fn;
      var $sql = '';
      var $params = '';
      var $host = '';
      var $database = '';
         
      function __construct($dbms, $fn, $errno, $errmsg, $p1, $p2, $thisConnection) {
         switch($fn) {
            case 'EXECUTE':
               $this->sql = $p1;
               $this->params = $p2;
               $s = "$dbms error: [$errno: $errmsg] in $fn(\"$p1\")\n";
               break;
            case 'PCONNECT':
            case 'CONNECT':
               $user = $thisConnection->user;
               $s = "$dbms error: [$errno: $errmsg] in $fn($p1, '$user', '****', $p2)\n";
               break;
            default:
               $s = "$dbms error: [$errno: $errmsg] in $fn($p1, $p2)\n";
               break;
         }
         $this->dbms = $dbms;
         $this->host = $thisConnection->host;
         $this->database = $thisConnection->database;
         $this->fn = $fn;
         $this->msg = $errmsg;
                
         if (!is_numeric($errno)) $errno = -1;
         parent::__construct($s,$errno);
      }
   }
   
   function oss_throw($dbms, $fn, $errno, $errmsg, $p1, $p2, $thisConnection) {
      global $OSS_EXCEPTION;
      if (error_reporting() == 0) return; // obey @ protocol
      if (is_string($OSS_EXCEPTION)) $errfn = $OSS_EXCEPTION;
      else $errfn = 'OSS_EXCEPTION';
      throw new $errfn($dbms, $fn, $errno, $errmsg, $p1, $p2, $thisConnection);
   }
?>
