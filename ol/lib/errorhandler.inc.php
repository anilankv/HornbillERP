<?php
   define('OSS_ERROR_HANDLER','Error_Handler');
   function Error_Handler($dbms, $fn, $errno, $errmsg, $p1=false, $p2=false)
   {
      global $lang, $dbErr_g;
      global $misc, $appName, $appVersion, $appLangFiles;
   
      switch($fn) {
         case 'EXECUTE':
            $sql = $p1;
            $inputparams = $p2;
      
            $s = "<p><b>{$lang['strsqlerror']}</b><br />" . $misc->printVal($errmsg) . "</p>
                  <p><b>{$lang['strinstatement']}</b><br />" . $misc->printVal($sql) . "</p>
            ";
//            echo "<table class=\"error\" cellpadding=\"5\"><tr><td>{$s}</td></tr></table>\n";
      
            break;
      
         case 'PCONNECT':
         case 'CONNECT':
            $_failed = true;
            include('index.php');
            exit;
            break;
         default:
            $s = "$dbms error: [$errno: $errmsg] in $fn($p1, $p2)\n";
//            echo "<table class=\"error\" cellpadding=\"5\"><tr><td>{$s}</td></tr></table>\n";
            break;
      }
      if (defined('OSS_ERROR_LOG_TYPE')) {
         $t = date('Y-m-d H:i:s');
         if (defined('OSS_ERROR_LOG_DEST'))
            error_log("($t) $s", OSS_ERROR_LOG_TYPE, OSS_ERROR_LOG_DEST);
         else
            error_log("($t) $s", OSS_ERROR_LOG_TYPE);
      }
//      $rtn = array() ;
      $rA['eH'] = $s ;
//      echo "(" . json_encode($rA) . ")" ;
//      exit ;
      //$dbErr_g = $s ;
   }
?>
