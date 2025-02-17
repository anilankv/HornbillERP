<?php
   if (!defined('OSS_DIR')) die();

   include_once(OSS_DIR."/drivers/oss_mysql.inc.php");
   class OSS_mysqlt extends OSS_mysql {
      var $databaseType = 'mysqlt';
      var $ansiOuter = true; // for Version 3.23.17 or later
      var $hasTransactions = true;
      var $autoRollback = true; // apparently mysql does not autorollback properly 
      
      function OSS_mysqlt() {         
         global $OSS_EXTENSION; if ($OSS_EXTENSION) $this->rsPrefix .= 'ext_';
      }
      
      function BeginTrans() {     
         if ($this->transOff) return true;
         $this->transCnt += 1;
         $this->Execute('SET AUTOCOMMIT=0');
         $this->Execute('BEGIN');
         return true;
      }
      
      function CommitTrans($ok=true) {
         if ($this->transOff) return true; 
         if (!$ok) return $this->RollbackTrans();
         
         if ($this->transCnt) $this->transCnt -= 1;
         $this->Execute('COMMIT');
         $this->Execute('SET AUTOCOMMIT=1');
         return true;
      }
      
      function RollbackTrans() {
         if ($this->transOff) return true;
         if ($this->transCnt) $this->transCnt -= 1;
         $this->Execute('ROLLBACK');
         $this->Execute('SET AUTOCOMMIT=1');
         return true;
      }
      
   }
   
   class OssRecordSet_mysqlt extends OssRecordSet_mysql{   
      var $databaseType = "mysqlt";
      
      function OssRecordSet_mysqlt($queryID,$mode=false) {
         if ($mode === false) { 
            global $OSS_FETCH_MODE;
            $mode = $OSS_FETCH_MODE;
         }
         switch ($mode) {
            case OSS_FETCH_NUM: $this->fetchMode = MYSQL_NUM; break;
            case OSS_FETCH_ASSOC:$this->fetchMode = MYSQL_ASSOC; break;
            default:
            case OSS_FETCH_DEFAULT:
            case OSS_FETCH_BOTH:$this->fetchMode = MYSQL_BOTH; break;
         }
      
         $this->OssRecordSet($queryID);   
      }
      
      function MoveNext() {
         if (@$this->fields =& mysql_fetch_array($this->_queryID,$this->fetchMode)) {
            $this->_currentRow += 1;
            return true;
         }
         if (!$this->EOF) {
            $this->_currentRow += 1;
            $this->EOF = true;
         }
         return false;
      }
   }
   
   class OssRecordSet_ext_mysqlt extends OssRecordSet_mysqlt {   
   
      function OssRecordSet_ext_mysqli($queryID,$mode=false) {
         if ($mode === false) { 
            global $OSS_FETCH_MODE;
            $mode = $OSS_FETCH_MODE;
         }
         switch ($mode) {
            case OSS_FETCH_NUM: $this->fetchMode = MYSQL_NUM; break;
            case OSS_FETCH_ASSOC:$this->fetchMode = MYSQL_ASSOC; break;
            default:
            case OSS_FETCH_DEFAULT:
            case OSS_FETCH_BOTH:$this->fetchMode = MYSQL_BOTH; break;
         }
         $this->OssRecordSet($queryID);   
      }
      
      function MoveNext() {
         return oss_movenext($this);
      }
   }
?>
