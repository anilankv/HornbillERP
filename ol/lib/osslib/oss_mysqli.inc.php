<?php

   if (!defined('OSS_DIR')) die();

   if (! defined("_OSS_MYSQLI_LAYER")) {
      define("_OSS_MYSQLI_LAYER", 1 );
      
      global $OSS_EXTENSION; $OSS_EXTENSION = false;
      
      class OSS_mysqli extends OssConnection {
         var $databaseType = 'mysqli';
         var $dataProvider = 'native';
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
         var $port = false;
         var $socket = false;
         var $_bindInputArray = false;
         var $nameQuote = '`';      /// string to use to quote identifiers and names
         
         function OSS_mysqli() {         
           if(!extension_loaded("mysqli"))
               trigger_error("You must have the mysqli extension installed.", E_USER_ERROR);
             
         }
         function _connect($argHostname = NULL, $argUsername = NULL, $argPassword = NULL, $argDatabasename = NULL, $persist=false)
           {
             $this->_connectionID = @mysqli_init();
             
             if (is_null($this->_connectionID)) {
               if ($this->debug) 
                  OssConnection::outp("mysqli_init() failed : "  . $this->ErrorMsg());
               return false;
             }
             if (mysqli_real_connect($this->_connectionID,
                       $argHostname,
                       $argUsername,
                       $argPassword,
                       $argDatabasename,
                     $this->port,
                     $this->socket,
                     $this->clientFlags))
                {
             if ($argDatabasename)  return $this->SelectDB($argDatabasename);
             return true;
             }
              else {
               if ($this->debug) 
                    OssConnection::outp("Could't connect : "  . $this->ErrorMsg());
               return false;
               }
           }
         
         function _pconnect($argHostname, $argUsername, $argPassword, $argDatabasename) {
            return $this->_connect($argHostname, $argUsername, $argPassword, $argDatabasename, true);
         }
         
         function _nconnect($argHostname, $argUsername, $argPassword, $argDatabasename) {
             $this->forceNewConnect = true;
             $this->_connect($argHostname, $argUsername, $argPassword, $argDatabasename);
           }
         
         function IfNull( $field, $ifNull ) {
            return " IFNULL($field, $ifNull) "; // if MySQL
         }
         
         function ServerInfo() {
            $arr['description'] = $this->GetOne("select version()");
            $arr['version'] = OssConnection::_findvers($arr['description']);
            return $arr;
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
         
         function qstr($s, $magic_quotes = false) {
            if (!$magic_quotes) {
                if (PHP_VERSION >= 5)
                     return "'" . mysqli_real_escape_string($this->_connectionID, $s) . "'";   
             
            if ($this->replaceQuote[0] == '\\')
               $s = oss_str_replace(array('\\',"\0"),array('\\\\',"\\\0"),$s);
             return  "'".str_replace("'",$this->replaceQuote,$s)."'"; 
           }
           $s = str_replace('\\"','"',$s);
           return "'$s'";
         }
         
         function _insertid() {
           $result = @mysqli_insert_id($this->_connectionID);
           if ($result == -1){
               if ($this->debug) OssConnection::outp("mysqli_insert_id() failed : "  . $this->ErrorMsg());
           }
           return $result;
         }
         
         function _affectedrows() {
           $result =  @mysqli_affected_rows($this->_connectionID);
           if ($result == -1) {
               if ($this->debug) OssConnection::outp("mysqli_affected_rows() failed : "  . $this->ErrorMsg());
           }
           return $result;
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
            $this->genID = mysqli_insert_id($this->_connectionID);
            
            if ($rs) $rs->Close();
            
            return $this->genID;
         }
         
         function &MetaDatabases() {
            $query = "SHOW DATABASES";
            $ret =& $this->Execute($query);
            return $ret;
         }
      
           
         function &MetaIndexes ($table, $primary = FALSE)
         {
            global $OSS_FETCH_MODE;
            $save = $OSS_FETCH_MODE;
            $OSS_FETCH_MODE = OSS_FETCH_NUM;
            if ($this->fetchMode !== FALSE) {
                   $savem = $this->SetFetchMode(FALSE);
            }
            $rs = $this->Execute(sprintf('SHOW INDEXES FROM %s',$table));
            if (isset($savem)) {
                    $this->SetFetchMode($savem);
            }
            $OSS_FETCH_MODE = $save;
            if (!is_object($rs)) {
                    return FALSE;
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
      
         function SQLDate($fmt, $col=false) {   
            if (!$col) $col = $this->sysTimeStamp;
            $s = 'DATE_FORMAT('.$col.",'";
            $concat = false;
            $len = strlen($fmt);
            for ($i=0; $i < $len; $i++) {
               $ch = $fmt[$i];
               switch($ch) {
               case 'Y':
               case 'y':
                  $s .= '%Y';
                  break;
               case 'Q':
               case 'q':
                  $s .= "'),Quarter($col)";
                  
                  if ($len > $i+1) $s .= ",DATE_FORMAT($col,'";
                  else $s .= ",('";
                  $concat = true;
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
                  
               default:
                  
                  if ($ch == '\\') {
                     $i++;
                     $ch = substr($fmt,$i,1);
                  }
                  $s .= $ch;
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
            if (!$date) 
              $date = $this->sysDate;
            return "from_unixtime(unix_timestamp($date)+($dayFraction)*24*3600)";
         }
         
         function &MetaColumns($table) {
            if (!$this->metaColumnsSQL) return false;
            
            global $OSS_FETCH_MODE;
            $save = $OSS_FETCH_MODE;
            $OSS_FETCH_MODE = OSS_FETCH_NUM;
            if ($this->fetchMode !== false)
               $savem = $this->SetFetchMode(false);
            $rs = $this->Execute(sprintf($this->metaColumnsSQL,$table));
            if (isset($savem)) $this->SetFetchMode($savem);
            $OSS_FETCH_MODE = $save;
            if (!is_object($rs)) return false;
            
            $retarr = array();
            while (!$rs->EOF) {
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
                $result = @mysqli_select_db($this->_connectionID, $dbName);
                if (!$result) {
                   OssConnection::outp("Select of database " . $dbName . " failed. " . $this->ErrorMsg());
                }
                return $result;      
             }
             return false;   
         }
         
         function &SelectLimit($sql, $nrows = -1, $offset = -1, $inputarr = false, $arg3 = false, $secs = 0) {
            $offsetStr = ($offset >= 0) ? "$offset," : '';
            if ($secs)
               $rs =& $this->CacheExecute($secs, $sql . " LIMIT $offsetStr$nrows" , $inputarr , $arg3);
            else $rs =& $this->Execute($sql . " LIMIT $offsetStr$nrows" , $inputarr , $arg3);
            return $rs;
         }
         
         function Prepare($sql) {
            return $sql;
            $stmt = $this->_connectionID->prepare($sql);
            if (!$stmt) {
               echo $this->ErrorMsg();
               return $sql;
            }
            return array($sql,$stmt);
         }
         
         function _query($sql, $inputarr) {
            global $OSS_COUNTRECS;
            if (is_array($sql)) {
               $stmt = $sql[1];
               $a = '';
               foreach($inputarr as $k => $v) {
                  if (is_string($v)) $a .= 's';
                  else if (is_integer($v)) $a .= 'i'; 
                  else $a .= 'd';
               }
               
               $fnarr =& array_merge( array($stmt,$a) , $inputarr);
               $ret = call_user_func_array('mysqli_stmt_bind_param',$fnarr);
      
               $ret = mysqli_stmt_execute($stmt);
               return $ret;
            }
            if (!$mysql_res =  mysqli_query($this->_connectionID, $sql, ($OSS_COUNTRECS) ? MYSQLI_STORE_RESULT : MYSQLI_USE_RESULT)) {
                if ($this->debug) OssConnection::outp("Query: " . $sql . " failed. " . $this->ErrorMsg());
                return false;
            }
            return $mysql_res;
         }
      
         function ErrorMsg() {
             if (empty($this->_connectionID)) $this->_errorMsg = @mysqli_error();
             else $this->_errorMsg = @mysqli_error($this->_connectionID);
             return $this->_errorMsg;
         }
         
         function ErrorNo() {
             if (empty($this->_connectionID))  return @mysqli_errno();
             else return @mysqli_errno($this->_connectionID);
         }
         
         function _close() {
             @mysqli_close($this->_connectionID);
             $this->_connectionID = false;
         }
      
         function CharMax() {
            return 255; 
         }
         
         function TextMax() {
            return 4294967295; 
         }
      }
       
      class OssRecordSet_mysqli extends OssRecordSet{   
         var $databaseType = "mysqli";
         var $canSeek = true;
         
         function OssRecordSet_mysqli($queryID, $mode = false) {
            if ($mode === false) { 
                global $OSS_FETCH_MODE;
                $mode = $OSS_FETCH_MODE;
             }
             
             switch ($mode) {
                case OSS_FETCH_NUM: 
                   $this->fetchMode = MYSQLI_NUM; 
                break;
                case OSS_FETCH_ASSOC:
                   $this->fetchMode = MYSQLI_ASSOC; 
                break;
                case OSS_FETCH_DEFAULT:
                case OSS_FETCH_BOTH:
                default:
                   $this->fetchMode = MYSQLI_BOTH; 
                break;
             }
             $this->ossFetchMode = $mode;
             $this->OssRecordSet($queryID);   
         }
         
         function _initrs() {
            global $OSS_COUNTRECS;
            $this->_numOfRows = $OSS_COUNTRECS ? @mysqli_num_rows($this->_queryID) : -1;
            $this->_numOfFields = @mysqli_num_fields($this->_queryID);
         }
         
         function &FetchField($fieldOffset = -1) {   
           $fieldnr = $fieldOffset;
           if ($fieldOffset != -1) {
             $fieldOffset = mysqli_field_seek($this->_queryID, $fieldnr);
           }
           $o = mysqli_fetch_field($this->_queryID);
           return $o;
         }
      
         function &GetRowAssoc($upper = true) {
            if ($this->fetchMode == MYSQLI_ASSOC && !$upper) return $this->fields;
            $row =& OssRecordSet::GetRowAssoc($upper);
            return $row;
         }
         
         function Fields($colname) {   
            if ($this->fetchMode != MYSQLI_NUM) return @$this->fields[$colname];
            if (!$this->bind) {
               $this->bind = array();
               for ($i = 0; $i < $this->_numOfFields; $i++) {
                  $o = $this->FetchField($i);
                  $this->bind[strtoupper($o->name)] = $i;
               }
            }
            return $this->fields[$this->bind[strtoupper($colname)]];
         }
         
         function _seek($row) {
            if ($this->_numOfRows == 0) return false;
            if ($row < 0) return false;
            mysqli_data_seek($this->_queryID, $row);
            $this->EOF = false;
            return true;
         }
            
         function MoveNext() {
            if ($this->EOF) return false;
            $this->_currentRow++;
            $this->fields = @mysqli_fetch_array($this->_queryID,$this->fetchMode);
            
            if (is_array($this->fields)) return true;
            $this->EOF = true;
            return false;
         }   
         
         function _fetch() {
            $this->fields = mysqli_fetch_array($this->_queryID,$this->fetchMode);  
              return is_array($this->fields);
         }
         
         function _close() {
            mysqli_free_result($this->_queryID); 
              $this->_queryID = false;   
         }
         
         function MetaType($t, $len = -1, $fieldobj = false) {
            if (is_object($t)) {
                $fieldobj = $t;
                $t = $fieldobj->type;
                $len = $fieldobj->max_length;
            }
            $len = -1; // mysql max_length is not accurate
            switch (strtoupper($t)) {
               case MYSQLI_TYPE_TINY_BLOB :
               case MYSQLI_TYPE_CHAR :
               case MYSQLI_TYPE_STRING :
               case MYSQLI_TYPE_ENUM :
               case MYSQLI_TYPE_SET :
               case 253 :
                  if ($len <= $this->blobSize) return 'C';
                  return 'X';
               
               case MYSQLI_TYPE_BLOB :
               case MYSQLI_TYPE_LONG_BLOB :
               case MYSQLI_TYPE_MEDIUM_BLOB :
                  return !empty($fieldobj->binary) ? 'B' : 'X';

               case MYSQLI_TYPE_DATE :
               case MYSQLI_TYPE_YEAR :
                  return 'D';
               
               case MYSQLI_TYPE_DATETIME :
               case MYSQLI_TYPE_NEWDATE :
               case MYSQLI_TYPE_TIME :
               case MYSQLI_TYPE_TIMESTAMP :
                  return 'T';
               
               case MYSQLI_TYPE_INT24 :
               case MYSQLI_TYPE_LONG :
               case MYSQLI_TYPE_LONGLONG :
               case MYSQLI_TYPE_SHORT :
               case MYSQLI_TYPE_TINY :
                  if (!empty($fieldobj->primary_key)) return 'R';
                  return 'I';
               
                default:
                   if (!is_numeric($t)) echo "<p>--- Error in type matching $t -----</p>"; 
                   return 'N';
            }
         } // function
      } // rs class
   }
?>
