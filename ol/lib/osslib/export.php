<?php
   
   function rs2csv(&$rs,$addtitles=true) {
      return _oss_export($rs,',',',',false,$addtitles);
   }
   function rs2csvfile(&$rs,$fp,$addtitles=true) {
      _oss_export($rs,',',',',$fp,$addtitles);
   }
   function rs2csvout(&$rs,$addtitles=true) {
      $fp = fopen('php://stdout','wb');
      _oss_export($rs,',',',',true,$addtitles);
      fclose($fp);
   }
   function rs2tab(&$rs,$addtitles=true) {
      return _oss_export($rs,"\t",',',false,$addtitles);
   }
   function rs2tabfile(&$rs,$fp,$addtitles=true) {
      _oss_export($rs,"\t",',',$fp,$addtitles);
   }
   function rs2tabout(&$rs,$addtitles=true) {
      $fp = fopen('php://stdout','wb');
      _oss_export($rs,"\t",' ',true,$addtitles);
      if ($fp) fclose($fp);
   }
   function _oss_export(&$rs,$sep,$sepreplace,$fp=false,$addtitles=true,$quote = '"',$escquote = '"',$replaceNewLine = ' ') {
      if (!$rs) return '';
      $NEWLINE = "\r\n";
      $BUFLINES = 100;
      $escquotequote = $escquote.$quote;
      $s = '';
      
      if ($addtitles) {
         $fieldTypes = $rs->FieldTypesArray();
         reset($fieldTypes);
         while(list(,$o) = each($fieldTypes)) {
            
            $v = $o->name;
            if ($escquote) $v = str_replace($quote,$escquotequote,$v);
            $v = strip_tags(str_replace("\n",$replaceNewLine,str_replace($sep,$sepreplace,$v)));
            $elements[] = $v;
            
         }
         $s .= implode($sep, $elements).$NEWLINE;
      }
      $hasNumIndex = isset($rs->fields[0]);
      
      $line = 0;
      $max = $rs->FieldCount();
      
      while (!$rs->EOF) {
         $elements = array();
         $i = 0;
         
         if ($hasNumIndex) {
            for ($j=0; $j < $max; $j++) {
               $v = $rs->fields[$j];
               if (!is_object($v)) $v = trim($v);
               else $v = 'Object';
               if ($escquote) $v = str_replace($quote,$escquotequote,$v);
               $v = strip_tags(str_replace("\n",$replaceNewLine,str_replace($sep,$sepreplace,$v)));
               
               if (strpos($v,$sep) !== false || strpos($v,$quote) !== false) $elements[] = "$quote$v$quote";
               else $elements[] = $v;
            }
         } else { 
            foreach($rs->fields as $v) {
               if ($escquote) $v = str_replace($quote,$escquotequote,trim($v));
               $v = strip_tags(str_replace("\n",$replaceNewLine,str_replace($sep,$sepreplace,$v)));
               
               if (strpos($v,$sep) !== false || strpos($v,$quote) !== false) $elements[] = "$quote$v$quote";
               else $elements[] = $v;
            }
         }
         $s .= implode($sep, $elements).$NEWLINE;
         $rs->MoveNext();
         $line += 1;
         if ($fp && ($line % $BUFLINES) == 0) {
            if ($fp === true) echo $s;
            else fwrite($fp,$s);
            $s = '';
         }
      }
      
      if ($fp) {
         if ($fp === true) echo $s;
         else fwrite($fp,$s);
         $s = '';
      }
      return $s;
   }
?>
