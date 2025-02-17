<?php
   
   if (!defined("DB_ERROR")) define("DB_ERROR",-1);
   if (!defined("DB_ERROR_SYNTAX")) {
      define("DB_ERROR_SYNTAX",              -2);
      define("DB_ERROR_CONSTRAINT",          -3);
      define("DB_ERROR_NOT_FOUND",           -4);
      define("DB_ERROR_ALREADY_EXISTS",      -5);
      define("DB_ERROR_UNSUPPORTED",         -6);
      define("DB_ERROR_MISMATCH",            -7);
      define("DB_ERROR_INVALID",             -8);
      define("DB_ERROR_NOT_CAPABLE",         -9);
      define("DB_ERROR_TRUNCATED",          -10);
      define("DB_ERROR_INVALID_NUMBER",     -11);
      define("DB_ERROR_INVALID_DATE",       -12);
      define("DB_ERROR_DIVZERO",            -13);
      define("DB_ERROR_NODBSELECTED",       -14);
      define("DB_ERROR_CANNOT_CREATE",      -15);
      define("DB_ERROR_CANNOT_DELETE",      -16);
      define("DB_ERROR_CANNOT_DROP",        -17);
      define("DB_ERROR_NOSUCHTABLE",        -18);
      define("DB_ERROR_NOSUCHFIELD",        -19);
      define("DB_ERROR_NEED_MORE_DATA",     -20);
      define("DB_ERROR_NOT_LOCKED",         -21);
      define("DB_ERROR_VALUE_COUNT_ON_ROW", -22);
      define("DB_ERROR_INVALID_DSN",        -23);
      define("DB_ERROR_CONNECT_FAILED",     -24);
      define("DB_ERROR_EXTENSION_NOT_FOUND",-25);
      define("DB_ERROR_NOSUCHDB",           -25);
      define("DB_ERROR_ACCESS_VIOLATION",   -26);
   }
   
   function oss_errormsg($value) {
      global $OSS_MSG_ARRAY;
      include_once(OSS_DIR."error_text.php");
      return isset($OSS_MSG_ARRAY[$value]) ? $OSS_MSG_ARRAY[$value] : $OSS_MSG_ARRAY[DB_ERROR];
   }
   
   function oss_error($provider,$dbType,$errno) {
      if (is_numeric($errno) && $errno == 0) return 0;
      switch($provider) { 
         case 'mysql': $map = oss_error_mysql(); break;
         case 'postgres': return oss_error_pg($errno); break;
         default: return DB_ERROR;
      }   
      if (isset($map[$errno])) return $map[$errno];
      return DB_ERROR;
   }
   
   function oss_error_pg($errormsg) {
      if (is_numeric($errormsg)) return (integer) $errormsg;
       static $error_regexps = array(
               '/(Table does not exist\.|Relation [\"\'].*[\"\'] does not exist|sequence does not exist|class ".+" not found)$/' => DB_ERROR_NOSUCHTABLE,
               '/Relation [\"\'].*[\"\'] already exists|Cannot insert a duplicate key into (a )?unique index.*/'      => DB_ERROR_ALREADY_EXISTS,
               '/divide by zero$/'                     => DB_ERROR_DIVZERO,
               '/pg_atoi: error in .*: can\'t parse /' => DB_ERROR_INVALID_NUMBER,
               '/ttribute [\"\'].*[\"\'] not found|Relation [\"\'].*[\"\'] does not have attribute [\"\'].*[\"\']/' => DB_ERROR_NOSUCHFIELD,
               '/parser: parse error at or near \"/'   => DB_ERROR_SYNTAX,
               '/referential integrity violation/'     => DB_ERROR_CONSTRAINT,
            '/Relation [\"\'].*[\"\'] already exists|Cannot insert a duplicate key into (a )?unique index.*|duplicate key violates unique constraint/'     
                 => DB_ERROR_ALREADY_EXISTS
           );
      reset($error_regexps);
      //while (list($regexp,$code) = each($error_regexps)) {
      foreach ($error_regexps as $regexp => $code) {
         if (preg_match($regexp, $errormsg)) {
            return $code;
         }
      }
      return DB_ERROR;
   }
      
   function oss_error_mysql() {
      static $MAP = array(
              1004 => DB_ERROR_CANNOT_CREATE,
              1005 => DB_ERROR_CANNOT_CREATE,
              1006 => DB_ERROR_CANNOT_CREATE,
              1007 => DB_ERROR_ALREADY_EXISTS,
              1008 => DB_ERROR_CANNOT_DROP,
              1045 => DB_ERROR_ACCESS_VIOLATION,
              1046 => DB_ERROR_NODBSELECTED,
              1049 => DB_ERROR_NOSUCHDB,
              1050 => DB_ERROR_ALREADY_EXISTS,
              1051 => DB_ERROR_NOSUCHTABLE,
              1054 => DB_ERROR_NOSUCHFIELD,
              1062 => DB_ERROR_ALREADY_EXISTS,
              1064 => DB_ERROR_SYNTAX,
              1100 => DB_ERROR_NOT_LOCKED,
              1136 => DB_ERROR_VALUE_COUNT_ON_ROW,
              1146 => DB_ERROR_NOSUCHTABLE,
              1048 => DB_ERROR_CONSTRAINT,
              2002 => DB_ERROR_CONNECT_FAILED
          );
         
      return $MAP;
   }
?>
