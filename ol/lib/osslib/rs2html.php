<?php 
   GLOBAL $gSQLMaxRows,$gSQLBlockRows;
   $gSQLMaxRows = 1000; // max no of rows to download
   $gSQLBlockRows=20; // max no of rows per table block
   
   function rs2html(&$rs,$ztabhtml=false,$zheaderarray=false,$htmlspecialchars=true,$echo = true) {
      $s ='';$rows=0;$docnt = false;
      GLOBAL $gSQLMaxRows,$gSQLBlockRows;
   
      if (!$rs) {
         printf(OSS_BAD_RS,'rs2html');
         return false;
      }
      
      if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
      $typearr = array();
      $ncols = $rs->FieldCount();
      $hdr = "<TABLE COLS=$ncols $ztabhtml><tr>\n\n";
      for ($i=0; $i < $ncols; $i++) {   
         $field = $rs->FetchField($i);
         if ($zheaderarray) $fname = $zheaderarray[$i];
         else $fname = htmlspecialchars($field->name);   
         $typearr[$i] = $rs->MetaType($field->type,$field->max_length);
            
         if (strlen($fname)==0) $fname = '&nbsp;';
         $hdr .= "<TH>$fname</TH>";
      }
      $hdr .= "\n</tr>";
      if ($echo) print $hdr."\n\n";
      else $html = $hdr;
      
      $numoffset = isset($rs->fields[0]) ||isset($rs->fields[1]) || isset($rs->fields[2]);
      while (!$rs->EOF) {
         
         $s .= "<TR valign=top>\n";
         
         for ($i=0; $i < $ncols; $i++) {
            if ($i===0) $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
            else $v = ($numoffset) ? $rs->fields[$i] : next($rs->fields);
            
            $type = $typearr[$i];
            switch($type) {
            case 'D':
               if (!strpos($v,':')) {
                  $s .= "   <TD>".$rs->UserDate($v,"D d, M Y") ."&nbsp;</TD>\n";
                  break;
               }
            case 'T':
               $s .= "   <TD>".$rs->UserTimeStamp($v,"D d, M Y, h:i:s") ."&nbsp;</TD>\n";
            break;
            case 'I':
            case 'N':
               $s .= "   <TD align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
                  
            break;
   
            default:
               if ($htmlspecialchars) $v = htmlspecialchars(trim($v));
               $v = trim($v);
               if (strlen($v) == 0) $v = '&nbsp;';
               $s .= "   <TD>". str_replace("\n",'<br>',stripslashes($v)) ."</TD>\n";
              
            }
         } // for
         $s .= "</TR>\n\n";
              
         $rows += 1;
         if ($rows >= $gSQLMaxRows) {
            $rows = "<p>Truncated at $gSQLMaxRows</p>";
            break;
         } // switch
   
         $rs->MoveNext();
      
         if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
            if ($echo) print $s . "</TABLE>\n\n";
            else $html .= $s ."</TABLE>\n\n";
            $s = $hdr;
         }
      } // while
   
      if ($echo) print $s."</TABLE>\n\n";
      else $html .= $s."</TABLE>\n\n";
      if ($docnt) if ($echo) print "<H2>".$rows." Rows</H2>";
      return ($echo) ? $rows : $html;
    }
    
   function arr2html(&$arr,$ztabhtml='',$zheaderarray='') {
      if (!$ztabhtml) $ztabhtml = 'BORDER=1';
      $s = "<TABLE $ztabhtml>";//';print_r($arr);
      if ($zheaderarray) {
         $s .= '<TR>';
         for ($i=0; $i<sizeof($zheaderarray); $i++) {
            $s .= "   <TH>{$zheaderarray[$i]}</TH>\n";
         }
         $s .= "\n</TR>";
      }
      for ($i=0; $i<sizeof($arr); $i++) {
         $s .= '<TR>';
         $a = &$arr[$i];
         if (is_array($a)) 
            for ($j=0; $j<sizeof($a); $j++) {
               $val = $a[$j];
               if (empty($val)) $val = '&nbsp;';
               $s .= "   <TD>$val</TD>\n";
            }
         else if ($a) {
            $s .=  '   <TD>'.$a."</TD>\n";
         } else $s .= "   <TD>&nbsp;</TD>\n";
         $s .= "\n</TR>\n";
      }
      $s .= '</TABLE>';
      print $s;
   }
?>
