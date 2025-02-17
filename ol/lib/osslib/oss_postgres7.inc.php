<?php
   if (!defined('OSS_DIR')) die();
//echo '<P> in oss_postgres7.inc.php loading driver ' . OSS_DIR . '</p>' ;
   
   include_once(OSS_DIR."/oss_postgres64.inc.php");
   
   class OSS_postgres7 extends OSS_postgres64 {
      var $databaseType = 'postgres7';   
      var $hasLimit = true;   // set to true for pgsql 6.5+ only. SELECT * FROM TABLE LIMIT 10
      var $ansiOuter = true;
      var $charSet = true; //set to true for Postgres 7 and above - PG client supports encodings
      
      function OSS_postgres7() {
         $this->OSS_postgres64();
//echo '<P> in oss_postgres7.inc.php loading  ' . $this->databaseType . '</p>' ;
         if (OSS_ASSOC_CASE !== 2) {
            $this->rsPrefix .= 'assoc_';
         }
      }
   
       function &SelectLimit($sql,$nrows=-1,$offset=-1,$inputarr=false,$secs2cache=0) {
          $offsetStr = ($offset >= 0) ? " OFFSET $offset" : '';
          $limitStr  = ($nrows >= 0)  ? " LIMIT $nrows" : '';
          if ($secs2cache)
              $rs =& $this->CacheExecute($secs2cache,$sql."$limitStr$offsetStr",$inputarr);
          else
              $rs =& $this->Execute($sql."$limitStr$offsetStr",$inputarr);
         
         return $rs;
       }
   function MetaForeignKeys($table, $owner=false, $upper=false) {
      $sql = 'SELECT t.tgargs as args
      FROM
      pg_trigger t,pg_class c,pg_proc p
      WHERE
      t.tgenabled AND
      t.tgrelid = c.oid AND
      t.tgfoid = p.oid AND
      p.proname = \'RI_FKey_check_ins\' AND
      c.relname = \''.strtolower($table).'\'
      ORDER BY
         t.tgrelid';
      
      $rs = $this->Execute($sql);
      
      if ($rs && !$rs->EOF) {
         $arr =& $rs->GetArray();
         $a = array();
         foreach($arr as $v)
         {
            $data = explode(chr(0), $v['args']);
            if ($upper) {
               $a[strtoupper($data[2])][] = strtoupper($data[4].'='.$data[5]);
            } else {
            $a[$data[2]][] = $data[4].'='.$data[5];
            }
         }
         return $a;
      }
      return false;
   }
   
   
   
       function xMetaForeignKeys($table, $owner=false, $upper=false) {
   
           $sql = '
   SELECT t.tgargs as args
      FROM pg_trigger t,
           pg_class c,
           pg_class c2,
           pg_proc f
      WHERE t.tgenabled
      AND t.tgrelid=c.oid
      AND t.tgconstrrelid=c2.oid
      AND t.tgfoid=f.oid
      AND f.proname ~ \'^RI_FKey_check_ins\'
      AND t.tgargs like \'$1\\\000'.strtolower($table).'%\'
      ORDER BY t.tgrelid';
   
           $rs = $this->Execute($sql);
         if ($rs && !$rs->EOF) {
            $arr =& $rs->GetArray();
            $a = array();
            foreach($arr as $v) {
                   $data = explode(chr(0), $v['args']);
                   if ($upper) {
                       $a[] = array(strtoupper($data[2]) => strtoupper($data[4].'='.$data[5]));
                   } else {
                       $a[] = array($data[2] => $data[4].'='.$data[5]);
                   }
                   
            }
            return $a;
         }
         else return false;
       }
      
      function GetCharSet() {
         $this->charSet = @pg_client_encoding($this->_connectionID);
         if (!$this->charSet) {
            return false;
         } else {
            return $this->charSet;
         }
      }
      
      function SetCharSet($charset_name) {
         $this->GetCharSet();
         if ($this->charSet !== $charset_name) {
            $if = pg_set_client_encoding($this->_connectionID, $charset_name);
            if ($if == "0" & $this->GetCharSet() == $charset_name) {
               return true;
            } else return false;
         } else return true;
      }
   
   }
   
   class OssRecordSet_postgres7 extends OssRecordSet_postgres64{
      var $databaseType = "postgres7";
      
      function OssRecordSet_postgres7($queryID,$mode=false) {
         $this->OssRecordSet_postgres64($queryID,$mode);
      }
      
      function MoveNext() {
         if (!$this->EOF) {
            $this->_currentRow++;
            if ($this->_numOfRows < 0 || $this->_numOfRows > $this->_currentRow) {
               $this->fields = @pg_fetch_array($this->_queryID,$this->_currentRow,$this->fetchMode);
            
               if (is_array($this->fields)) {
                  if ($this->fields && isset($this->_blobArr)) $this->_fixblobs();
                  return true;
               }
            }
            $this->fields = false;
            $this->EOF = true;
         }
         return false;
      }      
      function GetAll($nRows = -1) {
         return  @pg_fetch_all($this->_queryID);
      }      
   
   }
   
   class OssRecordSet_assoc_postgres7 extends OssRecordSet_postgres64{
   
      var $databaseType = "postgres7";
      
      function OssRecordSet_assoc_postgres7($queryID,$mode=false) {
         $this->OssRecordSet_postgres64($queryID,$mode);
      }
      
      function _fetch() {
         if ($this->_currentRow >= $this->_numOfRows && $this->_numOfRows >= 0)
              return false;
   
         $this->fields = @pg_fetch_array($this->_queryID,$this->_currentRow,$this->fetchMode);
         
         if ($this->fields) {
            if (isset($this->_blobArr)) $this->_fixblobs();
            $this->_updatefields();
         }
            
         return (is_array($this->fields));
      }
      
      function _updatefields() {
         if (OSS_ASSOC_CASE == 2) return; // native
      
         $arr = array();
         $lowercase = (OSS_ASSOC_CASE == 0);
         
         foreach($this->fields as $k => $v) {
            if (is_integer($k)) $arr[$k] = $v;
            else {
               if ($lowercase)
                  $arr[strtolower($k)] = $v;
               else
                  $arr[strtoupper($k)] = $v;
            }
         }
         $this->fields = $arr;
      }
      
      function MoveNext() {
         if (!$this->EOF) {
            $this->_currentRow++;
            if ($this->_numOfRows < 0 || $this->_numOfRows > $this->_currentRow) {
               $this->fields = @pg_fetch_array($this->_queryID,$this->_currentRow,$this->fetchMode);
            
               if (is_array($this->fields)) {
                  if ($this->fields) {
                     if (isset($this->_blobArr)) $this->_fixblobs();
                  
                     $this->_updatefields();
                  }
                  return true;
               }
            }
            
            
            $this->fields = false;
            $this->EOF = true;
         }
         return false;
      }
   }
?>
