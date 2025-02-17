<?php
   include_once( getcwd() . '/ol/lib/errorhandler.inc.php');
   include_once( getcwd() . '/ol/lib/adodb.inc.php');
   
   class OSS_base {
      var $conn;
      var $platform = 'UNKNOWN';
      public function __construct(&$conn) {
         $this->OSS_base($conn) ;
      }
      function OSS_base(&$conn) {
         $this->conn = $conn;
      }
   
      function setDebug($debug) {
         $this->conn->debug = $debug;
      }
   
      function clean(&$str) {
         $str = addslashes($str);
         return $str;
      }
      
      function fieldClean(&$str) {
         $str = str_replace('"', '""', $str);
         return $str;
      }
   
      function arrayClean(&$arr) {
         reset($arr);
         while(list($k, $v) = each($arr)) $arr[$k] = addslashes($v);
         return $arr;
      }
      
      function execute($sql) {
         $rs = $this->conn->Execute($sql);
         return $this->conn->ErrorNo();
      }
   
      function close() {
         $this->conn->Close();
      }
   
      function selectSet($sql) {
         $rs = $this->conn->Execute($sql);
         if (!$rs){
//echo '<P> in pg_oss.php selectSet returns error </P>' ;
	    return $this->conn->ErrorNo();
	 }
         return $rs;   
       }
       
      function selectField($sql, $field) {
         $rs = $this->conn->Execute($sql);
         if (!$rs) return $this->conn->ErrorNo();
         elseif ($rs->RecordCount() == 0) return -1;
         return $rs->f[$field];
      }
   
      function delete($table, $conditions) {
         $this->fieldClean($table);
         reset($conditions);
         $sql = '';
         while(list($key, $value) = each($conditions)) {
            $this->clean($key);
            $this->clean($value);
            if ($sql) $sql .= " AND \"{$key}\"='{$value}'";
            else $sql = "DELETE FROM \"{$table}\" WHERE \"{$key}\"='{$value}'";
         }
         if (!$this->conn->Execute($sql)) {
            if (stristr($this->conn->ErrorMsg(), 'referential')) return -1;
         }
         if ($this->conn->Affected_Rows() == 0) return -2;
         return $this->conn->ErrorNo();
      }
   
      function insert($table, $vars) {
         $this->fieldClean($table);
         if (sizeof($vars) > 0) {
            $fields = '';
            $values = '';
            foreach($vars as $key => $value) {
               $this->clean($key);
               $this->clean($value);
   
               if ($fields) $fields .= ", \"{$key}\"";
               else $fields = "INSERT INTO \"{$table}\" (\"{$key}\"";
   
               if ($values) $values .= ", '{$value}'";
               else $values = ") VALUES ('{$value}'";
            }
            $sql = $fields . $values . ')';
         }
         if (!$this->conn->Execute($sql)) {
            if (stristr($this->conn->ErrorMsg(), 'unique')) return -1;
            elseif (stristr($this->conn->ErrorMsg(), 'referential')) return -2;
         }
         return $this->conn->ErrorNo();
      }
   
      function update($table, $vars, $where, $nulls = array()) {

         $this->fieldClean($table);
         $setClause = '';
         $whereClause = '';
   
         reset($vars);
         while(list($key, $value) = each($vars)) {
            $this->fieldClean($key);
            $this->clean($value);
            if ($setClause) $setClause .= ", \"{$key}\"='{$value}'";
            else $setClause = "UPDATE \"{$table}\" SET \"{$key}\"='{$value}'";
         }
         reset($nulls);
         while(list(, $value) = each($nulls)) {
            $this->fieldClean($value);
            if ($setClause) $setClause .= ", \"{$value}\"=NULL";
            else $setClause = "UPDATE \"{$table}\" SET \"{$value}\"=NULL";
         }
         reset($where);
         while(list($key, $value) = each($where)) {
            $this->fieldClean($key);
            $this->clean($value);
            if ($whereClause) $whereClause .= " AND \"{$key}\"='{$value}'";
            else $whereClause = " WHERE \"{$key}\"='{$value}'";
         }
         if (!$this->conn->Execute($setClause . $whereClause)) {
            if (stristr($this->conn->ErrorMsg(), 'unique')) return -1;
            elseif (stristr($this->conn->ErrorMsg(), 'referential')) return -2;
         }
         if ($this->conn->Affected_Rows() == 0) return -3;
         return $this->conn->ErrorNo();
      }
      
      function beginTransaction() {
         return !$this->conn->BeginTrans();
      }
   
      function endTransaction() {
         return !$this->conn->CommitTrans();
      }
   
      function rollbackTransaction() {
         return !$this->conn->RollbackTrans();
      }
   
      function getPlatform() {
         return "UNKNOWN";
      }
   
      function dbBool(&$parameter) {
         return $parameter;
      }
   
      function phpBool($parameter) {
         return $parameter;
      }
   
      function phpArray($arr) {
         $temp = explode(',', substr($arr, 1, strlen($arr) - 2));
         for ($i = 0; $i < sizeof($temp); $i++) {
            if(substr($temp[$i], 0, 1) == '"' && substr($temp[$i], strlen($temp[$i]) - 1, 1)=='"'){
               $temp[$i] = substr($temp[$i], 1, strlen($temp[$i]) - 2);   
               $temp[$i] = str_replace('\\\\', '\\', $temp[$i]);
               $temp[$i] = str_replace('\\"', '"', $temp[$i]);
            }
         }
         return $temp;
      }
   }
?>
