<?php
   if (!defined('OSS_DIR')) die();
   
   if (! defined("_OSS_MYSQL_LAYER")) {
      define("_OSS_MYSQL_LAYER", 1 );
      
      class OSS_mysql extends OssConnection {
         var $databaseType = 'mysql';
         var $dataProvider = 'mysql';
         var $hasInsertID = true;
         var $hasAffectedRows = true;   
         var $metaTablesSQL = "SHOW TABLES";   
         var $metaColumnsSQL = "SHOW COLUMNS FROM %s";
         var $fmtTimeStamp = "'Y-m-d H:i:s'";
         var $hasLimit = true;
         var $hasMoveFirst = true;
         var $hasGenID = true;
         var $isoDates = true; // accepts dates in ISO format
         var $sysDate = 'CURDATE()';
         var $sysTimeStamp = 'NOW()';
         var $hasTransactions = false;
         var $forceNewConnect = false;
         var $poorAffectedRows = true;
         var $clientFlags = 0;
         var $substr = "substring";
         var $nameQuote = '`';      /// string to use to quote identifiers and names
         
         function OSS_mysql() {         
            if (defined('OSS_EXTENSION')) $this->rsPrefix .= 'ext_';
         }
         
         function ServerInfo() {
            $arr['description'] = OssConnection::GetOne("select version()");
            $arr['version'] = OssConnection::_findvers($arr['description']);
            return $arr;
         }
         
         function IfNull( $field, $ifNull ) {
            return " IFNULL($field, $ifNull) "; // if MySQL
         }
         
         function &MetaTables($ttype=false,$showSchema=false,$mask=false) {   
            $save = $this->metaTablesSQL;
            if ($showSchema && is_string($showSchema)) {
               $this->metaTablesSQL .= " from $showSchema";
            }
            
            if ($mask) {
               $mask = $this->qstr($mask);
               $this->metaTablesSQL .= " like $mask";
            }
            $ret =& OssConnection::MetaTables($ttype,$showSchema);
            
            $this->metaTablesSQL = $save;
            return $ret;
         }
         
         
         function &MetaIndexes ($table, $primary = FALSE, $owner=false) {
             global $OSS_FETCH_MODE;
              
            $false = false;
            $save = $OSS_FETCH_MODE;
            $OSS_FETCH_MODE = OSS_FETCH_NUM;
            if ($this->fetchMode !== FALSE) {
               $savem = $this->SetFetchMode(FALSE);
            }
            $rs = $this->Execute(sprintf('SHOW INDEX FROM %s',$table));
            if (isset($savem)) {
               $this->SetFetchMode($savem);
            }
            $OSS_FETCH_MODE = $save;
             
            if (!is_object($rs)) {
               return $false;
            }
              
            $indexes = array ();
              
            while ($row = $rs->FetchRow()) {
               if ($primary == FALSE AND $row[2] == 'PRIMARY') {
                   continue;
               }
                      
               if (!isset($indexes[$row[2]])) {
                   $indexes[$row[2]] = array(
                        'unique' => ($row[1] == 0),
                        'columns' => array()
                       );
               }
                      
               $indexes[$row[2]]['columns'][$row[3] - 1] = $row[4];
            }
            foreach ( array_keys ($indexes) as $index ) {
                ksort ($indexes[$index]['columns']);
            }
            return $indexes;
         }

         function qstr($s,$magic_quotes=false) {
            if (!$magic_quotes) {
               if (OSS_PHPVER >= 0x4300) {
                  if (is_resource($this->_connectionID))
                     return "'".mysql_real_escape_string($s,$this->_connectionID)."'";
               }
               if ($this->replaceQuote[0] == '\\'){
                  $s = oss_str_replace(array('\\',"\0"),array('\\\\',"\\\0"),$s);
               }
               return  "'".str_replace("'",$this->replaceQuote,$s)."'"; 
            }
            
            $s = str_replace('\\"','"',$s);
            return "'$s'";
         }
         
         function _insertid() {
            return mysql_insert_id($this->_connectionID);
         }
         
         function GetOne($sql,$inputarr=false) {
            if (strncasecmp($sql,'sele',4) == 0) {
               $rs =& $this->SelectLimit($sql,1,-1,$inputarr);
               if ($rs) {
                  $rs->Close();
                  if ($rs->EOF) return false;
                  return reset($rs->fields);
               }
            } else {
               return OssConnection::GetOne($sql,$inputarr);
            }
            return false;
         }
         
         function BeginTrans() {
            if ($this->debug) OssConnection::outp("Transactions not supported in 'mysql' driver. Use 'mysqlt' or 'mysqli' driver");
         }
         function _affectedrows() {
               return mysql_affected_rows($this->_connectionID);
         }
        
         var $_genIDSQL = "update %s set id=LAST_INSERT_ID(id+1);";
         var $_genSeqSQL = "create table %s (id int not null)";
         var $_genSeq2SQL = "insert into %s values (%s)";
         var $_dropSeqSQL = "drop table %s";
         
         function CreateSequence($seqname='ossseq',$startID=1) {
            if (empty($this->_genSeqSQL)) return false;
            $u = strtoupper($seqname);
            
            $ok = $this->Execute(sprintf($this->_genSeqSQL,$seqname));
            if (!$ok) return false;
            return $this->Execute(sprintf($this->_genSeq2SQL,$seqname,$startID-1));
         }
         
      
         function GenID($seqname='ossseq',$startID=1) {
            if (!$this->hasGenID) return false;
            $savelog = $this->_logsql;
            $this->_logsql = false;
            $getnext = sprintf($this->_genIDSQL,$seqname);
            $holdtransOK = $this->_transOK; // save the current status
            $rs = @$this->Execute($getnext);
            if (!$rs) {
               if ($holdtransOK) $this->_transOK = true; //if the status was ok before reset
               $u = strtoupper($seqname);
               $this->Execute(sprintf($this->_genSeqSQL,$seqname));
               $this->Execute(sprintf($this->_genSeq2SQL,$seqname,$startID-1));
               $rs = $this->Execute($getnext);
            }
            $this->genID = mysql_insert_id($this->_connectionID);
            
            if ($rs) $rs->Close();
            
            $this->_logsql = $savelog;
            return $this->genID;
         }
         
         function &MetaDatabases() {
            $qid = mysql_list_dbs($this->_connectionID);
            $arr = array();
            $i = 0;
            $max = mysql_num_rows($qid);
            while ($i < $max) {
               $db = mysql_tablename($qid,$i);
               if ($db != 'mysql') $arr[] = $db;
               $i += 1;
            }
            return $arr;
         }
         
         function SQLDate($fmt, $col=false) {   
            if (!$col) $col = $this->sysTimeStamp;
            $s = 'DATE_FORMAT('.$col.",'";
            $concat = false;
            $len = strlen($fmt);
            for ($i=0; $i < $len; $i++) {
               $ch = $fmt[$i];
               switch($ch) {
                  
               default:
                  if ($ch == '\\') {
                     $i++;
                     $ch = substr($fmt,$i,1);
                  }
                  /** FALL THROUGH */
               case '-':
               case '/':
                  $s .= $ch;
                  break;
                  
               case 'Y':
               case 'y':
                  $s .= '%Y';
                  break;
               case 'M':
                  $s .= '%b';
                  break;
                  
               case 'm':
                  $s .= '%m';
                  break;
               case 'D':
               case 'd':
                  $s .= '%d';
                  break;
               
               case 'Q':
               case 'q':
                  $s .= "'),Quarter($col)";
                  
                  if ($len > $i+1) $s .= ",DATE_FORMAT($col,'";
                  else $s .= ",('";
                  $concat = true;
                  break;
               
               case 'H': 
                  $s .= '%H';
                  break;
                  
               case 'h':
                  $s .= '%I';
                  break;
                  
               case 'i':
                  $s .= '%i';
                  break;
                  
               case 's':
                  $s .= '%s';
                  break;
                  
               case 'a':
               case 'A':
                  $s .= '%p';
                  break;
                  
      
               }
            }
            $s.="')";
            if ($concat) $s = "CONCAT($s)";
            return $s;
         }
         
         function Concat() {
            $s = "";
            $arr = func_get_args();
            
            $s = implode(',',$arr); 
            if (strlen($s) > 0) return "CONCAT($s)";
            else return '';
         }
         
         function OffsetDate($dayFraction,$date=false) {      
            if (!$date) $date = $this->sysDate;
            return "from_unixtime(unix_timestamp($date)+($dayFraction)*24*3600)";
         }
         
         function _connect($argHostname, $argUsername, $argPassword, $argDatabasename) {
            if (OSS_PHPVER >= 0x4300)
               $this->_connectionID = mysql_connect($argHostname,$argUsername,$argPassword,
                                          $this->forceNewConnect,$this->clientFlags);
            else if (OSS_PHPVER >= 0x4200)
               $this->_connectionID = mysql_connect($argHostname,$argUsername,$argPassword,
                                          $this->forceNewConnect);
            else
               $this->_connectionID = mysql_connect($argHostname,$argUsername,$argPassword);
         
            if ($this->_connectionID === false) return false;
            if ($argDatabasename) return $this->SelectDB($argDatabasename);
            return true;   
         }
         
         function _pconnect($argHostname, $argUsername, $argPassword, $argDatabasename) {
            if (OSS_PHPVER >= 0x4300)
               $this->_connectionID = mysql_pconnect($argHostname,$argUsername,$argPassword,$this->clientFlags);
            else
               $this->_connectionID = mysql_pconnect($argHostname,$argUsername,$argPassword);
            if ($this->_connectionID === false) return false;
            if ($this->autoRollback) $this->RollbackTrans();
            if ($argDatabasename) return $this->SelectDB($argDatabasename);
            return true;   
         }
         
         function _nconnect($argHostname, $argUsername, $argPassword, $argDatabasename) {
            $this->forceNewConnect = true;
            return $this->_connect($argHostname, $argUsername, $argPassword, $argDatabasename);
         }
         
         function &MetaColumns($table) {
         
            global $OSS_FETCH_MODE;
            $save = $OSS_FETCH_MODE;
            $OSS_FETCH_MODE = OSS_FETCH_NUM;
            if ($this->fetchMode !== false) $savem = $this->SetFetchMode(false);
            $rs = $this->Execute(sprintf($this->metaColumnsSQL,$table));
            if (isset($savem)) $this->SetFetchMode($savem);
            $OSS_FETCH_MODE = $save;
            if (!is_object($rs)) {
               $false = false;
               return $false;
            }
            $retarr = array();
            while (!$rs->EOF){
               $fld = new OssFieldObject();
               $fld->name = $rs->fields[0];
               $type = $rs->fields[1];
               
               $fld->scale = null;
               if (preg_match("/^(.+)\((\d+),(\d+)/", $type, $query_array)) {
                  $fld->type = $query_array[1];
                  $fld->max_length = is_numeric($query_array[2]) ? $query_array[2] : -1;
                  $fld->scale = is_numeric($query_array[3]) ? $query_array[3] : -1;
               } elseif (preg_match("/^(.+)\((\d+)/", $type, $query_array)) {
                  $fld->type = $query_array[1];
                  $fld->max_length = is_numeric($query_array[2]) ? $query_array[2] : -1;
               } else {
                  $fld->type = $type;
                  $fld->max_length = -1;
               }
               $fld->not_null = ($rs->fields[2] != 'YES');
               $fld->primary_key = ($rs->fields[3] == 'PRI');
               $fld->auto_increment = (strpos($rs->fields[5], 'auto_increment') !== false);
               $fld->binary = (strpos($type,'blob') !== false);
               $fld->unsigned = (strpos($type,'unsigned') !== false);
                  
               if (!$fld->binary) {
                  $d = $rs->fields[4];
                  if ($d != '' && $d != 'NULL') {
                     $fld->has_default = true;
                     $fld->default_value = $d;
                  } else {
                     $fld->has_default = false;
                  }
               }
               
               if ($save == OSS_FETCH_NUM) {
                  $retarr[] = $fld;
               } else {
                  $retarr[strtoupper($fld->name)] = $fld;
               }
                  $rs->MoveNext();
               }
            
               $rs->Close();
               return $retarr;   
         }
            
         function SelectDB($dbName) {
            $this->databaseName = $dbName;
            if ($this->_connectionID) {
               return @mysql_select_db($dbName,$this->_connectionID);      
            }
            else return false;   
         }
         
         function &SelectLimit($sql,$nrows=-1,$offset=-1,$inputarr=false,$secs=0) {
            $offsetStr =($offset>=0) ? "$offset," : '';
            if ($nrows < 0) $nrows = '18446744073709551615'; 
            
            if ($secs)
               $rs =& $this->CacheExecute($secs,$sql." LIMIT $offsetStr$nrows",$inputarr);
            else
               $rs =& $this->Execute($sql." LIMIT $offsetStr$nrows",$inputarr);
            return $rs;
         }
         
         function _query($sql,$inputarr) {
            return mysql_query($sql,$this->_connectionID);
         }
      
         function ErrorMsg() {
            if ($this->_logsql) return $this->_errorMsg;
            if (empty($this->_connectionID)) $this->_errorMsg = @mysql_error();
            else $this->_errorMsg = @mysql_error($this->_connectionID);
            return $this->_errorMsg;
         }
         
         function ErrorNo() {
            if ($this->_logsql) return $this->_errorCode;
            if (empty($this->_connectionID))  return @mysql_errno();
            else return @mysql_errno($this->_connectionID);
         }
         
         function _close() {
            @mysql_close($this->_connectionID);
            $this->_connectionID = false;
         }
      
         function CharMax() {
            return 255; 
         }
         
         function TextMax() {
            return 4294967295; 
         }
         
      }
         
      class OssRecordSet_mysql extends OssRecordSet{   
         var $databaseType = "mysql";
         var $canSeek = true;

         function OssRecordSet_mysql($queryID,$mode=false) {
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
            $this->ossFetchMode = $mode;
            $this->OssRecordSet($queryID);   
         }
         
         function _initrs() {
            $this->_numOfRows = @mysql_num_rows($this->_queryID);
            $this->_numOfFields = @mysql_num_fields($this->_queryID);
         }
         
         function &FetchField($fieldOffset = -1) {   
            if ($fieldOffset != -1) {
               $o = @mysql_fetch_field($this->_queryID, $fieldOffset);
               $f = @mysql_field_flags($this->_queryID,$fieldOffset);
               $o->max_length = @mysql_field_len($this->_queryID,$fieldOffset); 
               $o->binary = (strpos($f,'binary')!== false);
            }
            else if ($fieldOffset == -1) {   
               $o = @mysql_fetch_field($this->_queryID);
               $o->max_length = @mysql_field_len($this->_queryID); 
            }
            return $o;
         }
      
         function &GetRowAssoc($upper=true) {
            if ($this->fetchMode == MYSQL_ASSOC && !$upper) return $this->fields;
            $row =& OssRecordSet::GetRowAssoc($upper);
            return $row;
         }
         
         function Fields($colname) {   
            if ($this->fetchMode != MYSQL_NUM) return @$this->fields[$colname];
            
            if (!$this->bind) {
               $this->bind = array();
               for ($i=0; $i < $this->_numOfFields; $i++) {
                  $o = $this->FetchField($i);
                  $this->bind[strtoupper($o->name)] = $i;
               }
            }
             return $this->fields[$this->bind[strtoupper($colname)]];
         }
         
         function _seek($row) {
            if ($this->_numOfRows == 0) return false;
            return @mysql_data_seek($this->_queryID,$row);
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
         
         function _fetch() {
            $this->fields =  @mysql_fetch_array($this->_queryID,$this->fetchMode);
            return is_array($this->fields);
         }
         
         function _close() {
            @mysql_free_result($this->_queryID);   
            $this->_queryID = false;   
         }
         
         function MetaType($t,$len=-1,$fieldobj=false) {
            if (is_object($t)) {
               $fieldobj = $t;
               $t = $fieldobj->type;
               $len = $fieldobj->max_length;
            }
            
            $len = -1; // mysql max_length is not accurate
            switch (strtoupper($t)) {
            case 'STRING': 
            case 'CHAR':
            case 'VARCHAR': 
            case 'TINYBLOB': 
            case 'TINYTEXT': 
            case 'ENUM': 
            case 'SET': 
               if ($len <= $this->blobSize) return 'C';
               
            case 'TEXT':
            case 'LONGTEXT': 
            case 'MEDIUMTEXT':
               return 'X';
               
            case 'IMAGE':
            case 'LONGBLOB': 
            case 'BLOB':
            case 'MEDIUMBLOB':
               return !empty($fieldobj->binary) ? 'B' : 'X';
               
            case 'YEAR':
            case 'DATE': return 'D';
            
            case 'TIME':
            case 'DATETIME':
            case 'TIMESTAMP': return 'T';
            
            case 'INT': 
            case 'INTEGER':
            case 'BIGINT':
            case 'TINYINT':
            case 'MEDIUMINT':
            case 'SMALLINT': 
               
               if (!empty($fieldobj->primary_key)) return 'R';
               else return 'I';
            
            default: return 'N';
            }
         }
      
      }
      
      class OssRecordSet_ext_mysql extends OssRecordSet_mysql {   
         function OssRecordSet_ext_mysql($queryID,$mode=false) {
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
            $this->ossFetchMode = $mode;
            $this->OssRecordSet($queryID);
         }
         
         function MoveNext() {
            return @oss_movenext($this);
         }
      }
   }
?>
