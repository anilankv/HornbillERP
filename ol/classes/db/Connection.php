<?php
   GLOBAL $root_path ;
   include_once( getcwd() . '/ol/classes/db/OSS_base.php');
   class Connection {
      var $conn;
      var $platform = 'UNKNOWN';
      
      public function __construct($host,$port,$user,$pass,$db, $dbms, $fetchMode=ADODB_FETCH_ASSOC) {
         $this->Connection($host,$port,$user,$pass,$db, $dbms, $fetchMode) ;
      }
      function Connection($host,$port,$user,$pass,$db, $dbms, $fetchMode=ADODB_FETCH_ASSOC){
         $this->conn = &ADONewConnection($dbms);
         $this->conn->setFetchMode($fetchMode);
         if ($host === null || $host == '') $pghost = '';
         else $pghost = "{$host}:{$port}";
         $this->conn->Connect($pghost, $user, $pass, $db);
      }
   
      function getDriver(&$description) {
         $oss = new OSS_base($this->conn);
   
//         $sql = "SELECT VERSION() AS version";
//         $field = $oss->selectField($sql, 'version');
//   
//         if (eregi(' mingw ', $field)) $this->platform = 'MINGW';
//   
//         $params = explode(' ', $field);
//         if (!isset($params[1])) return -3;
//   
//         $version = $params[1]; // eg. 7.3.2
//         $description = "PostgreSQL {$params[1]}";
         $description = "Oss Data Object";
//   
//         if ((int)substr($version, 0, 1) < 7) return null;
//         elseif (strpos($version, '7.4') === 0) return 'Postgres74';
//         elseif (strpos($version, '7.3') === 0) return 'Postgres73';
//         elseif (strpos($version, '7.2') === 0) return 'Postgres72';
//         elseif (strpos($version, '7.1') === 0) return 'Postgres71';
//         elseif (strpos($version, '7.0') === 0) return 'Postgres';
//         else return 'Postgres80';
//echo '<P> connection Driver object ' . $this->conn->databaseType . '</P>' ;
//         return $this->conn->databaseType ;
         return 'postgres7' ;
      }
   
//      function getLastError() {      
//         if (function_exists('pg_errormessage')) return pg_errormessage($this->conn->_connectionID);
//         else return pg_last_error($this->conn->_connectionID);
//      }
   }
?>
