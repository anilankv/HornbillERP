<?php
   if (!defined('OSS_DIR')) die();
   global $OSS_INCLUDED_LIB;
   $OSS_INCLUDED_LIB = 1;
   
   function _array_change_key_case($an_array) {
      if (is_array($an_array)) {
         $new_array = array();
         foreach($an_array as $key=>$value) $new_array[strtoupper($key)] = $value;
         return $new_array;
      }
      return $an_array;
   }
   
   function _oss_replace(&$zthis, $table, $fieldArray, $keyCol, $autoQuote, $has_autoinc) {
      if (count($fieldArray) == 0) return 0;
      $first = true;
      $uSet = '';
         
      if (!is_array($keyCol)) {
         $keyCol = array($keyCol);
      }
      foreach($fieldArray as $k => $v) {
         if($autoQuote && !is_numeric($v) and strncmp($v,"'",1) !== 0 and strcasecmp($v,'null')!=0){
            $v = $zthis->qstr($v);
            $fieldArray[$k] = $v;
         }
         if (in_array($k,$keyCol)) continue; // skip UPDATE if is key
         if ($first) {
            $first = false;         
            $uSet = "$k=$v";
         } else $uSet .= ",$k=$v";
      }
      $where = false;
      foreach ($keyCol as $v) {
         if ($where) $where .= " and $v=$fieldArray[$v]";
         else $where = "$v=$fieldArray[$v]";
      }
      if ($uSet && $where) {
         $update = "UPDATE $table SET $uSet WHERE $where";
         $rs = $zthis->Execute($update);
         if ($rs) {
            if ($zthis->poorAffectedRows) {
               if ($zthis->ErrorNo()<>0) return 0;
               $cnt = $zthis->GetOne("select count(*) from $table where $where");
               if ($cnt > 0) return 1; // record already exists
            } else {
               if (($zthis->Affected_Rows()>0)) return 1;
            }
         } else return 0;
      }
      $first = true;
      foreach($fieldArray as $k => $v) {
         if ($has_autoinc && in_array($k,$keyCol)) continue; // skip autoinc col
         if ($first) {
            $first = false;         
            $iCols = "$k";
            $iVals = "$v";
         } else {
            $iCols .= ",$k";
            $iVals .= ",$v";
         }            
      }
      $insert = "INSERT INTO $table ($iCols) VALUES ($iVals)"; 
      $rs = $zthis->Execute($insert);
      return ($rs) ? 2 : 0;
   }
   
   function _oss_getmenu(&$zthis, $name,$defstr='',$blank1stItem=true,$multiple=false, $size=0, $selectAttr='',$compareFields0=true) {
      $hasvalue = false;
   
      if ($multiple or is_array($defstr)) {
         if ($size==0) $size=5;
         $attr = ' multiple size="'.$size.'"';
         if (!strpos($name,'[]')) $name .= '[]';
      } else if ($size) $attr = ' size="'.$size.'"';
      else $attr ='';
      
      $s = '<select name="'.$name.'"'.$attr.' '.$selectAttr.'>';
      if ($blank1stItem) 
         if (is_string($blank1stItem))  {
            $barr = explode(':',$blank1stItem);
            if (sizeof($barr) == 1) $barr[] = '';
            $s .= "\n<option value=\"".$barr[0]."\">".$barr[1]."</option>";
         } else $s .= "\n<option></option>";
   
      if ($zthis->FieldCount() > 1) $hasvalue=true;
      else $compareFields0 = true;
      
      $value = '';
      while(!$zthis->EOF) {
         $zval = rtrim(reset($zthis->fields));
         if (sizeof($zthis->fields) > 1) {
            if (isset($zthis->fields[1])) $zval2 = rtrim($zthis->fields[1]);
            else $zval2 = rtrim(next($zthis->fields));
         }
         $selected = ($compareFields0) ? $zval : $zval2;
         if ($blank1stItem && $zval=="") {
            $zthis->MoveNext();
            continue;
         }
         if ($hasvalue) $value = " value='".htmlspecialchars($zval2)."'"; 
         if (is_array($defstr))  {
            if (in_array($selected,$defstr)) 
               $s .= "<option selected='selected'$value>".htmlspecialchars($zval).'</option>';
            else $s .= "\n<option".$value.'>'.htmlspecialchars($zval).'</option>';
         } else {
            if (strcasecmp($selected,$defstr)==0) 
               $s .= "<option selected='selected'$value>".htmlspecialchars($zval).'</option>';
            else $s .= "\n<option".$value.'>'.htmlspecialchars($zval).'</option>';
         }
         $zthis->MoveNext();
      } // while
      return $s ."\n</select>\n";
   }
   
   function _oss_getcount(&$zthis, $sql,$inputarr=false,$secs2cache=0) {
      $qryRecs = 0;
      if (preg_match("/^\s*SELECT\s+DISTINCT/is", $sql) || preg_match('/\s+GROUP\s+BY\s+/is',$sql)){
         if ( $zthis->databaseType == 'postgres' || $zthis->databaseType == 'postgres7')  {
            $info = $zthis->ServerInfo();
            if (substr($info['version'],0,3) >= 7.1) { // good till version 999
               $rewritesql = preg_replace('/(\sORDER\s+BY\s.*)/is','',$sql);
               $rewritesql = "SELECT COUNT(*) FROM ($rewritesql) _OSS_ALIAS_";
            }
         }
      } else { 
         $rewritesql = preg_replace( '/^\s*SELECT\s.*\s+FROM\s/Uis','SELECT COUNT(*) FROM ',$sql);
         $rewritesql = preg_replace('/(\sORDER\s+BY\s.*)/is','',$rewritesql); 
      }
      if (isset($rewritesql) && $rewritesql != $sql) {
         if ($secs2cache) {
            $qryRecs = $zthis->CacheGetOne($secs2cache/2,$rewritesql,$inputarr);
         } else {
            $qryRecs = $zthis->GetOne($rewritesql,$inputarr);
           }
         if ($qryRecs !== false) return $qryRecs;
      }
      if (preg_match('/\s*UNION\s*/is', $sql)) $rewritesql = $sql;
      else $rewritesql = preg_replace('/(\sORDER\s+BY\s.*)/is','',$sql); 
      
      $rstest = &$zthis->Execute($rewritesql,$inputarr);
      if ($rstest) {
         $qryRecs = $rstest->RecordCount();
         if ($qryRecs == -1) { 
            global $OSS_EXTENSION;
            if ($OSS_EXTENSION) {
               while(!$rstest->EOF) {
                  oss_movenext($rstest);
               }
            } else {
               while(!$rstest->EOF) {
                  $rstest->MoveNext();
               }
            }
            $qryRecs = $rstest->_currentRow;
         }
         $rstest->Close();
         if ($qryRecs == -1) return 0;
      }
      return $qryRecs;
   }
   
   function &_oss_pageexecute_all_rows(&$zthis,$sql,$nrows,$page,$inputarr=false,$secs2cache=0) {
      $atfirstpage = false;
      $atlastpage = false;
      $lastpageno=1;
      if (!isset($nrows) || $nrows <= 0) $nrows = 10;
      $qryRecs = false; //count records for no offset
      $qryRecs = _oss_getcount($zthis,$sql,$inputarr,$secs2cache);
      $lastpageno = (int) ceil($qryRecs / $nrows);
      $zthis->_maxRecordCount = $qryRecs;
      if ($page >= $lastpageno) {
         $page = $lastpageno;
         $atlastpage = true;
      }
      if (empty($page) || $page <= 1) {   
         $page = 1;
         $atfirstpage = true;
      }
      $offset = $nrows * ($page-1);
      if ($secs2cache > 0) 
         $rsreturn = &$zthis->CacheSelectLimit($secs2cache, $sql, $nrows, $offset, $inputarr);
      else $rsreturn = &$zthis->SelectLimit($sql, $nrows, $offset, $inputarr, $secs2cache);
      if ($rsreturn) {
         $rsreturn->_maxRecordCount = $qryRecs;
         $rsreturn->rowsPerPage = $nrows;
         $rsreturn->AbsolutePage($page);
         $rsreturn->AtFirstPage($atfirstpage);
         $rsreturn->AtLastPage($atlastpage);
         $rsreturn->LastPageNo($lastpageno);
      }
      return $rsreturn;
   }
   
   function &_oss_pageexecute_no_last_page(&$zthis,$sql,$nrows,$page,$inputarr=false,$secs2cache=0){
      $atfirstpage = false;
      $atlastpage = false;
      if (!isset($page) || $page <= 1) {   // If page number <= 1, then we are at the first page
         $page = 1;
         $atfirstpage = true;
      }
      if ($nrows <= 0) $nrows = 10;   // If an invalid nrows is supplied, we assume a value of 10 
      $pagecounter = $page + 1;
      $pagecounteroffset = ($pagecounter * $nrows) - $nrows;
      if ($secs2cache>0) $rstest = &$zthis->CacheSelectLimit($secs2cache, $sql, $nrows, $pagecounteroffset, $inputarr);
      else $rstest = &$zthis->SelectLimit($sql, $nrows, $pagecounteroffset, $inputarr, $secs2cache);
      if ($rstest) {
         while ($rstest && $rstest->EOF && $pagecounter>0) {
            $atlastpage = true;
            $pagecounter--;
            $pagecounteroffset = $nrows * ($pagecounter - 1);
            $rstest->Close();
            if ($secs2cache>0) $rstest = &$zthis->CacheSelectLimit($secs2cache, $sql, $nrows, $pagecounteroffset, $inputarr);
            else $rstest = &$zthis->SelectLimit($sql, $nrows, $pagecounteroffset, $inputarr, $secs2cache);
         }
         if ($rstest) $rstest->Close();
      }
      if ($atlastpage) {   // If we are at the last page or beyond it, we are going to retrieve it
         $page = $pagecounter;
         if ($page == 1) $atfirstpage = true;   // do again if the lastpage is the same as first
      }
      $offset = $nrows * ($page-1);
      if ($secs2cache > 0) $rsreturn = &$zthis->CacheSelectLimit($secs2cache, $sql, $nrows, $offset, $inputarr);
      else $rsreturn = &$zthis->SelectLimit($sql, $nrows, $offset, $inputarr, $secs2cache);
      if ($rsreturn) {
         $rsreturn->rowsPerPage = $nrows;
         $rsreturn->AbsolutePage($page);
         $rsreturn->AtFirstPage($atfirstpage);
         $rsreturn->AtLastPage($atlastpage);
      }
      return $rsreturn;
   }
   
   function _oss_getupdatesql(&$zthis,&$rs, $arrFields,$forceUpdate=false,$magicq=false,$force=2) {
      if (!$rs) {
         printf(OSS_BAD_RS,'GetUpdateSQL');
         return false;
      }
      $fieldUpdatedCount = 0;
      $arrFields = _array_change_key_case($arrFields);
      $hasnumeric = isset($rs->fields[0]);
      $setFields = '';

      for ($i=0, $max=$rs->FieldCount(); $i < $max; $i++) {
         $field = $rs->FetchField($i);
         $upperfname = strtoupper($field->name);
         if (oss_key_exists($upperfname,$arrFields,$force)) {
            if ($hasnumeric) $val = $rs->fields[$i];
            else if (isset($rs->fields[$upperfname])) $val = $rs->fields[$upperfname];
            else if (isset($rs->fields[$field->name])) $val =  $rs->fields[$field->name];
            else if (isset($rs->fields[strtolower($upperfname)])) $val =  $rs->fields[strtolower($upperfname)];
            else $val = '';
            if ($forceUpdate || strcmp($val, $arrFields[$upperfname])) {
               $fieldUpdatedCount++;
               $type = $rs->MetaType($field->type);
               if ($type == 'null') {
                  $type = 'C';
               }
               if (strpos($upperfname,' ') !== false)
                  $fnameq = $zthis->nameQuote.$upperfname.$zthis->nameQuote;
               else $fnameq = $upperfname;
               if (is_null($arrFields[$upperfname])
                  || (empty($arrFields[$upperfname]) && strlen($arrFields[$upperfname]) == 0)
                       || $arrFields[$upperfname] === 'null') {
                       switch ($force) {
                           case 1:
                               $setFields .= $field->name . " = null, ";
                           break;
                        
                           case 2:
                               $arrFields[$upperfname] = "";
                               $setFields .= _oss_column_sql($zthis, 'U', $type, $upperfname, $fnameq,$arrFields, $magicq);
                           break;
                     default:
                           case 3:
                               if (is_null($arrFields[$upperfname]) || $arrFields[$upperfname] === 'null') {
                                   $setFields .= $field->name . " = null, ";
                               } else {
                                   $setFields .= _oss_column_sql($zthis, 'U', $type, $upperfname, $fnameq,$arrFields, $magicq);
                               }
                           break;
                    }
               } else {
                  $setFields .= _oss_column_sql($zthis, 'U', $type, $upperfname, $fnameq,
                                               $arrFields, $magicq);
               }
            }
         }
      }
      if ($fieldUpdatedCount > 0 || $forceUpdate) {
         preg_match("/FROM\s+".OSS_TABLE_REGEX."/is", $rs->sql, $tableName);
         preg_match('/\sWHERE\s(.*)/is', $rs->sql, $whereClause);
           
         $discard = false;
         if ($whereClause) {
            if (preg_match('/\s(ORDER\s.*)/is', $whereClause[1], $discard));
            else if (preg_match('/\s(LIMIT\s.*)/is', $whereClause[1], $discard));
            else preg_match('/\s(FOR UPDATE.*)/is', $whereClause[1], $discard);
         } else $whereClause = array(false,false);
             
         if ($discard)
            $whereClause[1] = substr($whereClause[1], 0, strlen($whereClause[1]) - strlen($discard[1]));
         $sql = 'UPDATE '.$tableName[1].' SET '.substr($setFields, 0, -2);
         if (strlen($whereClause[1]) > 0) $sql .= ' WHERE '.$whereClause[1];
   
         return $sql;
   
      } else {
         return false;
      }
   }
   
   function oss_key_exists($key, &$arr,$force=2) {
      if ($force<=0) {
         return (!empty($arr[$key])) || (isset($arr[$key]) && strlen($arr[$key])>0);
      }
      if (isset($arr[$key])) return true;
      if (OSS_PHPVER >= 0x4010) return array_key_exists($key,$arr);
      return false;
   }
   
   function _oss_getinsertsql(&$zthis,&$rs,$arrFields,$magicq=false,$force=2) {
      static $cacheRS = false;
      static $cacheSig = 0;
      static $cacheCols;
   
      $tableName = '';
      $values = '';
      $fields = '';
      $recordSet = null;
      $arrFields = _array_change_key_case($arrFields);
      $fieldInsertedCount = 0;
      
      if (is_string($rs)) {
         $tableName = $rs;         
         $rsclass = $zthis->rsPrefix.$zthis->databaseType;
         $recordSet =& new $rsclass(-1,$zthis->fetchMode);
         $recordSet->connection = &$zthis;
         
         if (is_string($cacheRS) && $cacheRS == $rs) {
            $columns =& $cacheCols;
         } else {
            $columns = $zthis->MetaColumns( $tableName );
            $cacheRS = $tableName;
            $cacheCols = $columns;
         }
      } else if (is_subclass_of($rs, 'ossrecordset')) {
         if (isset($rs->insertSig) && is_integer($cacheRS) && $cacheRS == $rs->insertSig) {
            $columns =& $cacheCols;
         } else {
            for ($i=0, $max=$rs->FieldCount(); $i < $max; $i++) 
               $columns[] = $rs->FetchField($i);
            $cacheRS = $cacheSig;
            $cacheCols = $columns;
            $rs->insertSig = $cacheSig++;
         }
         $recordSet =& $rs;
      
      } else {
         printf(OSS_BAD_RS,'GetInsertSQL');
         return false;
      }
   
      foreach( $columns as $field ) { 
         $upperfname = strtoupper($field->name);
         if (oss_key_exists($upperfname,$arrFields,$force)) {
            $bad = false;
            if (strpos($upperfname,' ') !== false)
               $fnameq = $zthis->nameQuote.$upperfname.$zthis->nameQuote;
            else
               $fnameq = $upperfname;
            
            $type = $recordSet->MetaType($field->type);
               if (is_null($arrFields[$upperfname])
                   || (empty($arrFields[$upperfname]) && strlen($arrFields[$upperfname]) == 0)
                   || $arrFields[$upperfname] === 'null') {
                       switch ($force) {
                           case 0: // we must always set null if missing
                               $bad = true;
                           break;
                           case 1:
                               $values  .= "null, ";
                           break;
         
                           case 2:
                               $arrFields[$upperfname] = "";
                               $values .= _oss_column_sql($zthis, 'I', $type, $upperfname, $fnameq,$arrFields, $magicq);
                           break;
   
                     default:
                           case 3:
                        if (is_null($arrFields[$upperfname]) || $arrFields[$upperfname] === 'null') { 
                           $values  .= "null, ";
                        } else {
                                 $values .= _oss_column_sql($zthis, 'I', $type, $upperfname, $fnameq, $arrFields, $magicq);
                            }
                          break;
                      } // switch
            } else {
               $values .= _oss_column_sql($zthis, 'I', $type, $upperfname, $fnameq,
                                       $arrFields, $magicq);
            }
            
            if ($bad) continue;
            $fieldInsertedCount++;
            $fields .= $fnameq . ", ";
         }
      }
      if ($fieldInsertedCount <= 0)  return false;
      if (!$tableName) {
         if (preg_match("/FROM\s+".OSS_TABLE_REGEX."/is", $rs->sql, $tableName))
            $tableName = $tableName[1];
         else return false;
      }      
      $fields = substr($fields, 0, -2);
      $values = substr($values, 0, -2);
      return 'INSERT INTO '.$tableName.' ( '.$fields.' ) VALUES ( '.$values.' )';
   }
   
   function _oss_column_sql(&$zthis, $action, $type, $fname, $fnameq, $arrFields, $magicq, $recurse=true) {
      if ($recurse) {
         switch($zthis->dataProvider)  {
         case 'postgres':
            if ($type == 'L') $type = 'C';
            break;
         }
      }
      $sql = '';
      switch($type) {
         case "C":
         case "X":
         case 'B':
            if ($action == 'I') {
               $sql = $zthis->qstr($arrFields[$fname],$magicq) . ", ";
            } else {
               $sql .= $fnameq . "=" . $zthis->qstr($arrFields[$fname],$magicq) . ", ";
            }
           break;
   
         case "D":
            if ($action == 'I') {
               $sql = $zthis->DBDate($arrFields[$fname]) . ", ";
            } else {
               $sql .= $fnameq . "=" . $zthis->DBDate($arrFields[$fname]) . ", ";
            }
            break;
   
         case "T":
            if ($action == 'I') {
               $sql = $zthis->DBTimeStamp($arrFields[$fname]) . ", ";
            } else {
               $sql .= $fnameq . "=" . $zthis->DBTimeStamp($arrFields[$fname]) . ", ";
            }
            break;
   
         default:
            $val = $arrFields[$fname];
            if (empty($val)) $val = '0';
            if ($action == 'I') {
               $sql .= $val . ", ";
            } else {
               $sql .= $fnameq . "=" . $val  . ", ";
            }
            break;
      }
      return $sql;
   }
   
   function _oss_debug_execute(&$zthis, $sql, $inputarr) {
      global $HTTP_SERVER_VARS;
      $ss = '';
      if ($inputarr) {
         foreach($inputarr as $kk=>$vv) {
            if (is_string($vv) && strlen($vv)>64) $vv = substr($vv,0,64).'...';
            $ss .= "($kk=>'$vv') ";
         }
         $ss = "[ $ss ]";
      }
      $sqlTxt = str_replace(',',', ',is_array($sql) ? $sql[0] : $sql);
      $inBrowser = isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']);
      if ($inBrowser) {
         $ss = htmlspecialchars($ss);
         if ($zthis->debug === -1)
            OssConnection::outp( "<br>\n($zthis->databaseType): ".htmlspecialchars($sqlTxt)." &nbsp; <code>$ss</code>\n<br>\n",false);
         else 
            OssConnection::outp( "<hr>\n($zthis->databaseType): ".htmlspecialchars($sqlTxt)." &nbsp; <code>$ss</code>\n<hr>\n",false);
      } else {
         OssConnection::outp("-----\n($zthis->databaseType): ".$sqlTxt."\n-----\n",false);
      }
      $qID = $zthis->_query($sql,$inputarr);
      if (!$qID) {
         OssConnection::outp($zthis->ErrorNo() .': '. $zthis->ErrorMsg());
      }
      return $qID;
   }
   
   function _oss_backtrace($printOrArr=true,$levels=9999) {
      if (PHPVERSION() < 4.3) return '';
      $html =  (isset($_SERVER['HTTP_USER_AGENT']));
      $fmt =  ($html) ? "</font><font color=#808080 size=-1> %% line %4d, file: <a href=\"file:/%s\">%s</a></font>" : "%% line %4d, file: %s";
   
      $MAXSTRLEN = 64;
      $s = ($html) ? '<pre align=left>' : '';
      if (is_array($printOrArr)) $traceArr = $printOrArr;
      else $traceArr = debug_backtrace();
      array_shift($traceArr);
      array_shift($traceArr);
      $tabs = sizeof($traceArr)-2;
      foreach ($traceArr as $arr) {
         $levels -= 1;
         if ($levels < 0) break;
         
         $args = array();
         for ($i=0; $i < $tabs; $i++) $s .=  ($html) ? ' &nbsp; ' : "\t";
         $tabs -= 1;
         if ($html) $s .= '<font face="Courier New,Courier">';
         if (isset($arr['class'])) $s .= $arr['class'].'.';
         if (isset($arr['args']))
          foreach($arr['args'] as $v) {
            if (is_null($v)) $args[] = 'null';
            else if (is_array($v)) $args[] = 'Array['.sizeof($v).']';
            else if (is_object($v)) $args[] = 'Object:'.get_class($v);
            else if (is_bool($v)) $args[] = $v ? 'true' : 'false';
            else {
               $v = (string) @$v;
               $str = htmlspecialchars(substr($v,0,$MAXSTRLEN));
               if (strlen($v) > $MAXSTRLEN) $str .= '...';
               $args[] = $str;
            }
         }
         $s .= $arr['function'].'('.implode(', ',$args).')';
         $s .= @sprintf($fmt, $arr['line'],$arr['file'],basename($arr['file']));
         $s .= "\n";
      }   
      if ($html) $s .= '</pre>';
      if ($printOrArr) print $s;
      return $s;
   }
?>
