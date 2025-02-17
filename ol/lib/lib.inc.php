<?php
   $rA = array() ;
//   $rA['dbgM'] = array() ;
   $rv = array() ;
   $dbgCnt = 0 ;
   $root_path = $_SERVER["DOCUMENT_ROOT"] . '/' ;
   function rsGetFlds(&$rs) {
      $a = array() ;
      if ( is_object($rs)) {
         $ncols = $rs->FieldCount();
         for ($i=0; $i < $ncols; $i++) {   
            $a[$i] = $rs->FetchField($i)->name ;
         }
      }
      return $a ;
   }
   function rsGetData(&$rs,$htmlspecialchars=true) {
      $a = array() ;
      $ncols = $rs->FieldCount();
      $numoffset = isset($rs->fields[0]) ||isset($rs->fields[1]) || isset($rs->fields[2]);
      $j = 0 ;
      if ( is_object($rs)) while (!$rs->EOF) {
         $a[$j] = array() ;
         for ($i=0; $i < $ncols; $i++) {
            if ($i===0) $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
            else $v = ($numoffset) ? $rs->fields[$i] : next($rs->fields);
            if ($htmlspecialchars) $v = htmlspecialchars(trim($v));
            $v = trim($v);
            $a[$j][$i] = $v;
         } 
         $j++ ;
         $rs->MoveNext();
      } 
      return $a ;
   }
   function rsGetArrangedData(&$rs,$htmlspecialchars=true) {
      $a = array() ;
      if ( is_object($rs)) {
         $ncols = $rs->FieldCount();
         $numoffset = isset($rs->fields[0]) ||isset($rs->fields[1]) || isset($rs->fields[2]);
         while (!$rs->EOF) {
            for ($i=0; $i < $ncols; $i++) {
               if ($i===0) $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
               else $v = ($numoffset) ? $rs->fields[$i] : next($rs->fields);
               if ($htmlspecialchars) $v = htmlspecialchars(trim($v));
               $v = trim($v);
               if ( $i == 0 ){
                  $j = $v ;
                  $a[$j] = array() ;
               } 
               $a[$j][$i] = $v;
            } 
            $rs->MoveNext();
         } 
      } 
      return $a ;
   }
   function rs2array(&$rs,$htmlspecialchars=true) {
      $a = array() ;
      if ( is_object($rs)) {
         $a[0] = rsGetFlds($rs) ;
         $ncols = $rs->FieldCount();
         $numoffset = isset($rs->fields[0]) ||isset($rs->fields[1]) || isset($rs->fields[2]);
         $j = 1 ;
         while (!$rs->EOF) {
            $a[$j] = array() ;
            for ($i=0; $i < $ncols; $i++) {
               if ($i===0) $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
               else $v = ($numoffset) ? $rs->fields[$i] : next($rs->fields);
               if ($htmlspecialchars) $v = htmlspecialchars(trim($v));
               $v = trim($v);
               $a[$j][$i] = $v;
            } 
            $j++ ;
            $rs->MoveNext();
         } 
      } 
      return $a ;
   }
   function hdrfetch($htag) {
      global $hdr_g ; 
      return $hdr_g[$htag] ;
   } ;
   function fetch() {
      global $HTTP_RAW_POST_DATA;
      $jsn = empty($HTTP_RAW_POST_DATA) ? file_get_contents("php://input") : $HTTP_RAW_POST_DATA;
      $out = preg_replace_callback('!s:(\d+):"(.*?)";!s',function($m){ return 's:'.strlen($m[2]).':"' . $m[2] . '";'; }, $jsn ) ;
      return Unserializer( $out ) ;
   }
   function Unserializer($data) {
      if ( !(is_string($data)) ) {
         return $data;
      }
      $result = @unserialize(trim($data));
      return $result;
   }
   function encode_ip($dotquad_ip) {
      $ip = explode('.', $dotquad_ip);
      return sprintf('%02x%02x%02x%02x', $ip[0], $ip[1], $ip[2], $ip[3]);
   }
   function setQryValMatch ($m) {
      global $rV_g ;
      if ($rV_g[$m[1]]) if ($rV_g[$m[1]][$m[2]]) if ($rV_g[$m[1]][$m[2]][$m[3]]) {
         return $rV_g[$m[1]][$m[1]][$m[1]];
      }
      return '' ;
   }
   function getQryFrmPrimitive($pq, $eD) {
      global $rA ;
      global $rip_g ;
      global $gid_g ;
      global $sid_g ;
      global $uid_g ;
      global $org_g ;
      global $unt_g ;
      global $unt_u ;
      global $ent_g ;
      global $sbu_g ;
      global $mnu ;
      global $aS_g ;
      global $srv_g ;
      global $srvdt_g ;
      global $rsrv_g ;
      global $_SESSION ;
      global $nEv_g ;
      global $sEv_g ;
      global $aEv_g ;
      global $nS_g ;
      global $sNm_g ;
      global $st_g ;
      global $key_g ;
      $pat = "/" . '@v\(\d*[^@]\)@\(\d*[^@]\)@\(\d*[^ ]\)' . "/" ;
      $qry = preg_replace_callback( $pat, "setQryValMatch", $pq ) ;
      $pat = "/" . '\$rip_g' . "/" ;
      $qry = preg_replace( $pat, $rip_g, $qry ) ;
      $pat = "/" . '\$gid_g' . "/" ;
      $qry = preg_replace( $pat, $gid_g, $qry ) ;
      $pat = "/" . '\$sid_g' . "/" ;
      $qry = preg_replace( $pat, $sid_g, $qry ) ;
      $pat = "/" . '\$uid_g' . "/" ;
      $qry = preg_replace( $pat, $uid_g, $qry ) ;
      $pat = "/" . '\$org_g' . "/" ;
      $qry = preg_replace( $pat, $org_g, $qry ) ;
      $pat = "/" . '\$unt_g' . "/" ;
      $qry = preg_replace( $pat, $unt_g, $qry ) ;
      $pat = "/" . '\$unt_u' . "/" ;
      $qry = preg_replace( $pat, $unt_u, $qry ) ;
      $pat = "/" . '\$sbu_g' . "/" ;
      $qry = preg_replace( $pat, $sbu_g, $qry ) ;
      $pat = "/" . '\$ent_g' . "/" ;
      $qry = preg_replace( $pat, $ent_g, $qry ) ;
      $pat = "/" . '\$mnu' . "/" ;
      $qry = preg_replace( $pat, $_SESSION['mnu'], $qry ) ;
      $pat = "/" . '\$unm_g' . "/" ;
      $qry = preg_replace( $pat, $_SESSION['unm'], $qry ) ;
      $pat = "/" . '\$eid_e' . "/" ; // Id of login employee
      $qry = preg_replace( $pat, $_SESSION['eid_e'], $qry ) ;  
      $pat = "/" . '\$enm_e' . "/" ; // Name of login employee
      $qry = preg_replace( $pat, $_SESSION['enm_e'], $qry ) ;  
      $pat = "/" . '\$eno_e' . "/" ; // Eno of login employee
      $qry = preg_replace( $pat, $_SESSION['eno_e'], $qry ) ;  
      $pat = "/" . '\$etp_e' . "/" ; // Employee type of login employee
      $qry = preg_replace( $pat, $_SESSION['etp_e'], $qry ) ;  
      $pat = "/" . '\$cod_e' . "/" ; // Account code of login employee
      $qry = preg_replace( $pat, $_SESSION['cod_e'], $qry ) ;  
      $pat = "/" . '\$pst_s' . "/" ; // Post of session role
      $qry = preg_replace( $pat, $_SESSION['pst_s'], $qry ) ;  
      $pat = "/" . '\$pst_e' . "/" ; // Post of login employee
      $qry = preg_replace( $pat, $_SESSION['pst_e'], $qry ) ;
      $pat = "/" . '\$unt_ep' . "/" ; // Unit of employee post
      $qry = preg_replace( $pat, $_SESSION['unt_ep'], $qry ) ;
      $pat = "/" . '\$dsg_ep' . "/" ; // Designation of employee post
      $qry = preg_replace( $pat, $_SESSION['dsg_ep'], $qry ) ;
      $pat = "/" . '\$dep_ep' . "/" ; // Department of employee post
      $qry = preg_replace( $pat, $_SESSION['dep_ep'], $qry ) ;
      $pat = "/" . '\$gid_ep' . "/" ; // Roll of employee post
      $qry = preg_replace( $pat, $_SESSION['gid_ep'], $qry ) ;
      $pat = "/" . '\$rof_ep' . "/" ; // Reporting post of employee post
      $qry = preg_replace( $pat, $_SESSION['rof_ep'], $qry ) ;
      //$pat = "/" . '\$sof_ep' . "/" ; // Supervisor post of employee post
      //$qry = preg_replace( $pat, $_SESSION['sof_ep'], $qry ) ;
      $pat = "/" . '\$svc_ep' . "/" ; // Svc of employee post
      $qry = preg_replace( $pat, $_SESSION['svc_ep'], $qry ) ;
      $pat = "/" . '\$sbu_ep' . "/" ; // Sub unit of employee post
      $qry = preg_replace( $pat, $_SESSION['sbu_ep'], $qry ) ;
      $pat = "/" . '\$unt_sp' . "/" ; // Unit of session post
      $qry = preg_replace( $pat, $_SESSION['unt_sp'], $qry ) ;
      $pat = "/" . '\$dsg_sp' . "/" ; // Designation of session post
      $qry = preg_replace( $pat, $_SESSION['dsg_sp'], $qry ) ;
      $pat = "/" . '\$dep_sp' . "/" ; // Department of session post
      $qry = preg_replace( $pat, $_SESSION['dep_sp'], $qry ) ;
      $pat = "/" . '\$gid_sp' . "/" ; // Roll of session post
      $qry = preg_replace( $pat, $_SESSION['gid_sp'], $qry ) ;
      $pat = "/" . '\$rof_sp' . "/" ; // Reporting session of employee post
      $qry = preg_replace( $pat, $_SESSION['rof_sp'], $qry ) ;
      //$pat = "/" . '\$sof_sp' . "/" ; // Supervisor session of employee post
      //$qry = preg_replace( $pat, $_SESSION['sof_sp'], $qry ) ;
      $pat = "/" . '\$svc_sp' . "/" ; // Svc of session post
      $qry = preg_replace( $pat, $_SESSION['svc_sp'], $qry ) ;
      $pat = "/" . '\$sbu_sp' . "/" ; // Sub unit of session post
      $qry = preg_replace( $pat, $_SESSION['sbu_sp'], $qry ) ;
      $pat = "/" . '\$snm' . "/" ;
      $qry = preg_replace( $pat, $sNm_g, $qry ) ;
      $pat = "/" . '\$aS_g' . "/" ;
      $qry = preg_replace( $pat, $aS_g, $qry ) ;
      $pat = "/" . '\$nS_g' . "/" ;
      $qry = preg_replace( $pat, $nS_g, $qry ) ;
      $pat = "/" . '\$nEv_g' . "/" ;
      $qry = preg_replace( $pat, $nEv_g, $qry ) ;
      $pat = "/" . '\$sEv_g' . "/" ;
      $qry = preg_replace( $pat, $sEv_g, $qry ) ;
      $pat = "/" . '\$aEv_g' . "/" ;
      $qry = preg_replace( $pat, $aEv_g, $qry ) ;
      $pat = "/" . '\$rsrv_g' . "/" ;
      $qry = preg_replace( $pat, $rsrv_g, $qry ) ;
      $pat = "/" . '\$srv_g' . "/" ;
      $qry = preg_replace( $pat, $srv_g, $qry ) ;
      $pat = "/" . '\$st_g' . "/" ;
      $qry = preg_replace( $pat, $st_g, $qry ) ;
      $pat = "/" . "'\$k_g'" . "/" ;
      $qry = preg_replace( $pat, $key_g, $qry ) ;
      $pat = "/" . '\$k_g' . "/" ;
      $qry = preg_replace( $pat, $key_g, $qry ) ;
      if(isset( $rA[0])){
         $pat = "/##[^']*/" ;
         $qry = preg_replace( $pat, $rA[0] , $qry ) ;
      } else if (isset( $eD['k'])) {
         $pat = "/##[^']*/" ;
         $qry = preg_replace( $pat, $eD['k'] , $qry ) ;
         $key_g = empty($key_g) ? $eD['k'] : $key_g ;
      }
      if(!empty($eD)) {
         foreach ($eD as $hdl => $sDt){
            if(is_array($sDt)) continue ;
            $bDt = $sDt ;
            if ( $hdl == 'itemValidation' ) continue ;
            if ( $hdl == '__t' ) continue ;
            if ( $hdl == '__q' ) continue ;
            if((!(isset( $rA[0])) ) &&  (isset( $eD['k'])) ){
               $pat = "/'##". $hdl . "'/"  ;
               $rstr = "/'#". $hdl . "'/"  ;
               $qry = preg_replace( $pat, "$rstr", $qry ) ;
            }
            $sDt = str_replace("'", "''", $sDt ) ;
            $pat = "/'#". $hdl . "'/" ;
            $qry = ($sDt == 'NULL')? preg_replace( $pat, "$sDt", $qry ) : preg_replace( $pat, "'$sDt'", $qry ) ;
            $pat = "/ #". $hdl . " /" ;
            $qry = ($sDt == 'NULL')? preg_replace( $pat, "$sDt", $qry ) : preg_replace( $pat, " $sDt ", $qry ) ;
            $pat = "/^#". $hdl . "$/" ;
            $qry = ($sDt == 'NULL')? preg_replace( $pat, "$sDt", $qry ) : preg_replace( $pat, " $sDt ", $qry ) ;
            $pat = "/\|#". $hdl . " /" ;
            $qry = ($bDt == 'NULL')? preg_replace( $pat, "$bDt", $qry ) : preg_replace( $pat, " $bDt ", $qry ) ;
         }
      }
      $pat = "/'#[^']*'/" ;
      $qry = preg_replace( $pat, 'NULL' , $qry ) ;
      return $qry ;
   }
   $mSts_g = 0;
   function inv_req($head, $msg, $lvl = 0) {
      global $rA, $mSts_g  ;
      info_box( "__Invalid", $msg, 1 ) ;
      ob_start();
      include_once(getcwd() . '/ol/home.php');
      $rA['h'] = ob_get_contents();
      ob_end_clean();
      echo "(" . json_encode($rA) . ")" ;
      exit ;
   }
   function info_box($head, $msg, $lvl = 0) {
     global $rA, $mSts_g  ;
     if( $lvl > 0 ){
        $mSts_g = 1;
        $rA[$head] = $msg  ;
     }
   }
   function getOS() {
      global $uA_g ;
      $a = array (    
         'Linux'=>'s','iPhone'=>'p','Win[0-9]'=>'p','Windows'=>'p','OpenBSD'=>'s','Mac'=>'p','QNX'=>'p','OS/2'=>'p','BeOS'=>'p',
         'nuhk' => 'x', 'Googlebot' =>'x', 'Yammybot'=>'x', 'Openbot'=>'x', 'Slurp' =>'x', 'msnbot' =>'x', 'ia_archiver'=>'x') ;
      foreach($a as $nam=>$b){if(preg_match("/$nam/", $uA_g)) {return $b;} } return 'x'; 
   } 
   function getBrwsr() {
      global $uA_g ;
      $a = array(
         'Chrome' => 'v', 'Opera'=>'s','Firebird'=>'s','Firefox'=>'s','Galeon'=>'s','Gecko'=>'s',
         'MyIE' => 'p', 'Lynx' => 's','Mozilla\/' => 's', 'Konqueror' => 's',
         'nuhk' => 'x', 'Googlebot' =>'x', 'Yammybot'=>'x', 'Openbot'=>'x', 'Slurp' =>'x', 'msnbot' =>'x', 'ia_archiver'=>'x', 'MSIE'=>'p' ) ;
      foreach($a as $nam=>$b){if(preg_match("/$nam/", $uA_g)) {return $b;} } return 'x'; 
   } 
   function pmVldt() {
      global $data ;
      global $rA ;
      global $ent_g ;
      global $f_g ;
      global $p_g ;
      global $pL_g ;
      global $m_g ;
      global $m0_g ;
      global $m1_g ;
      global $m2_g ;
      global $m3_g ;
      global $m4_g ;
      global $srv_g ;
      global $aS_g ;
      global $nS_g ;
      global $rsrv_g ;
      global $ssrv_g ;
      global $sNm_g ;
      global $wNm_g ;
      global $sEv_g ;
      global $nEv_g ;
      global $aEv_g ;
      global $st_g ;
      global $key_g ;
      global $v_g ;
      global $c_g ;
      global $a_g ;
      global $sct_g ;
      global $s_g ;
      global $iA_g ;
      global $fno_g ;
      global $ftp_g ;
      global $rmk_g ;
      global $mod_g ;
      global $dmd_g ;
      global $m5_g ;
      global $mnu_g ;
      global $hid_g ;
      global $rfr_g ;
      global $hrp_g ;
      global $smd_g ;
      global $usr_g ;
      if ( !(empty($p_g)) && !preg_match('/^[0-9]*$/', $p_g) ) {
        inv_req( "__Invalid", "Invalid Page" , 1 ) ;
      }      
      if ( !(empty($pL_g)) && !preg_match('/^[0-9]*$/', $pL_g) ) {
        inv_req( "__Invalid", "Invalid Page size" , 1 ) ;
      }      
      if ( !(empty($m_g)) && !preg_match('/^[0-9]*$/', $m_g) ) {
        inv_req( "__Invalid", "Invalid menu" , 1 ) ;
      }      
      if ( !(empty($m0_g)) && !preg_match('/^[0-9]*$/', $m0_g) ) {
        inv_req( "__Invalid", "Invalid menu level 0" , 1 ) ;
      }      
      if ( !(empty($m1_g)) && !preg_match('/^[0-9]*$/', $m1_g) ) {
        inv_req( "__Invalid", "Invalid menu level 1" , 1 ) ;
      }      
      if ( !(empty($m2_g)) && !preg_match('/^[0-9]*$/', $m2_g) ) {
        inv_req( "__Invalid", "Invalid menu level 2" , 1 ) ;
      }      
      if ( !(empty($m3_g)) && !preg_match('/^[0-9]*$/', $m3_g) ) {
        inv_req( "__Invalid", "Invalid menu level 3" , 1 ) ;
      }      
      if ( !(empty($m4_g)) && !preg_match('/^[0-9]*$/', $m4_g) ) {
        inv_req( "__Invalid", "Invalid menu level 4" , 1 ) ;
      }      
      if ( !(empty($m5_g)) && !preg_match('/^[0-9]*$/', $m5_g) ) {
        inv_req( "__Invalid", "Invalid menu level 5" , 1 ) ;
      }      
      if ( !(empty($f_g)) && !preg_match('/^[0-9]$/', $f_g) ) {
        inv_req( "__Invalid", "Invalid factor" , 1 ) ;
      }      
      if (!(empty($srv_g))) {
         if ( !preg_match('/^[0-9]*$/', $srv_g) ) {
            inv_req( "__Invalid", "Invalid service" , 1 ) ;
         } else {
            $q = "select id from sw.srv where id = '$srv_g' " ;
            $rs = $data->selectSet( $q );
            if (!is_object($rs) || ($rs->EOF) ) {
               inv_req( "__Invalid", "Invalid Service" , 1 ) ;
            }  
         }  
      }  
      if ( !empty($ssrv_g) && ($ssrv_g != '-1') ) {
         if ( !preg_match('/^[0-9]*$/', $ssrv_g) ) {
            inv_req( "__Invalid", "Invalid sub service" , 1 ) ;
         } else {
            $q = "select id from sw.srv where id = '$ssrv_g' " ;
            $rs = $data->selectSet( $q );
            if (!is_object($rs) || ($rs->EOF) ) {
               inv_req( "__Invalid", "Invalid sub Service" , 1 ) ;
            }  
         }  
      }  
      if ( !empty($rsrv_g) && ($rsrv_g != '-1') ) {
         if ( !preg_match('/^[0-9]*$/', $rsrv_g) ) {
            inv_req( "__Invalid", "Invalid reference" , 1 ) ;
         } else {
            $q = "select id from sw.srv where id = '$rsrv_g' " ;
            $rs = $data->selectSet( $q );
            if (!is_object($rs) || ($rs->EOF) ) {
               inv_req( "__Invalid", "Invalid reference" , 1 ) ;
            }  
         }  
      }  
      if ( !empty($s_g) ) {
         if ( preg_match('/[0-9;"\']/', $s_g) ) {
            inv_req( "__Invalid", "Invalid string ($s_g)" , 1 ) ;
         }
      }
      if (!(empty($aS_g))) {
         if ( !preg_match('/^[0-9]*$/', $aS_g) ) {
            inv_req( "__Invalid", "Invalid action service" , 1 ) ;
         } else {
            $q = "select id from sw.srv where id = '$aS_g' " ;
            $rs = $data->selectSet( $q );
            if (!is_object($rs) || ($rs->EOF) ) {
               inv_req( "__Invalid", "Invalid action service" , 1 ) ;
            }  
         }  
      }  
      if (!(empty($nS_g))) {
         if ( !preg_match('/^[0-9]*$/', $nS_g) ) {
            inv_req( "__Invalid", "Invalid next service" , 1 ) ;
         } else {
            $q = "select id from sw.srv where id = '$nS_g' " ;
            $rs = $data->selectSet( $q );
            if (!is_object($rs) || ($rs->EOF) ) {
               inv_req( "__Invalid", "Invalid next service" , 1 ) ;
            }  
         }  
      }  
      if ( !empty($sNm_g)) {
         if ( !empty($wNm_g)) {
            $q = "select wnm from sw.widget where snm = '$sNm_g' and wnm='$wNm_g' " ;
            $rs = $data->selectSet( $q );
            if (!is_object($rs) || ($rs->EOF) ) {
               inv_req( "__Invalid", "Invalid widget" , 1 ) ;
            }  
         }  
         $q = "select snm from sw.screen where snm = '$sNm_g' " ;
         $rs = $data->selectSet( $q );
         if (!is_object($rs) || ($rs->EOF) ) {
            inv_req( "__Invalid", "Invalid screen" , 1 ) ;
         }  
      }  
      if ( !empty($sEv_g)) {
         $q = "SELECT s.id, w.eid from sw.srv s, sw.screen w where w.snm ='$sNm_g' and s.snm='$sNm_g' and s.act = '$sEv_g' " ;
         $rs = $data->selectSet( $q );
         if (!is_object($rs) || ($rs->EOF) ) {
            inv_req( "__Invalid", "Invalid event ($sEv_g)" , 1 ) ;
         }  
      }  
      if ( !empty($nEv_g)) {
         $q = "SELECT s.id, w.eid from sw.srv s, sw.screen w where w.snm ='$sNm_g' and s.snm='$sNm_g' and s.act = '$nEv_g' " ;
         $rs = $data->selectSet( $q );
         if (!is_object($rs) || ($rs->EOF) ) {
            inv_req( "__Invalid", "Invalid next action" , 1 ) ;
         }  
      }  
      if ( !empty($aEv_g)) {
         $q = "SELECT s.id, w.eid from sw.srv s, sw.screen w where w.snm ='$sNm_g' and s.snm='$sNm_g' and s.act = '$aEv_g' " ;
         $rs = $data->selectSet( $q );
         if (!is_object($rs) || ($rs->EOF) ) {
            inv_req( "__Invalid", "Invalid action" , 1 ) ;
         }  
      }  
      if ( !empty($st_g)) {
         $q = "SELECT abr from sw.action where abr='$st_g' " ;
         $rs = $data->selectSet( $q );
         if (!is_object($rs) || ($rs->EOF) ) {
            inv_req( "__Invalid", "Invalid stage" , 1 ) ;
         }  
      }  
      if ( !empty($smd_g)) {
         $q = "SELECT abr from sw.action where abr='$smd_g' " ;
         $rs = $data->selectSet( $q );
         if (!is_object($rs) || ($rs->EOF) ) {
            inv_req( "__Invalid", "Invalid mode ($smd_g)" , 1 ) ;
         }  
      }  
      if ( !empty($dmd_g)) {
         if ( !preg_match('/^[d]$/', $dmd_g) && !preg_match('/^inline$/', $dmd_g) ) {
            inv_req( "__Invalid", "Invalid data request" , 1 ) ;
         }  
      }  
      if ( !empty($ent_g) && ($ent_g != '-1') ) {
         if ( !preg_match('/^[0-9]*$/', $ent_g) ) {
            inv_req( "__Invalid", "Invalid entity" , 1 ) ;
         } else {
            $q = "select id from sw.entity where id = '$ent_g' " ;
            $rs = $data->selectSet( $q );
            if (!is_object($rs) || ($rs->EOF) ) {
               inv_req( "__Invalid", "Invalid entity" , 1 ) ;
            }  
         }  
      }      
      //$Cnd = empty($d_g['qP_g']) ? '' : getCnd( $d_g['qP_g']);
      //$oBy = empty($d_g['qP_g']) ? '' : getOby( $d_g['qP_g']);
      //$ofs = empty($d_g['qP_g']) ? '0' : getOfs( $d_g['qP_g']);
      //$flt = empty($d_g['qP_g']) ? '*' : getFlt( $d_g['qP_g'], $sct);
   } 
//   function sEmail($to, $sbj, $cnt, $pa = false, $cc = '', $bcc = '') {
//      $rn = "\r\n";
//      $by = md5(rand());
//      $byc = md5(rand());
//      $h = 'From: '. $conf['replyEmail'] . $rn;
//      $h .= 'Mime-Version: 1.0' . $rn;
//      $h .= 'Content-Type: multipart/related;boundary=' . $by . $rn;
//      if (!empty($cc) ) $h .= 'Cc: ' . $cc . $rn;
//      if (!empty($bcc) ) $h .= 'Bcc: ' . $bcc . $rn;
//      $h .= $rn;
//      $m = $rn . '--' . $by . $rn;
//      $m.= "Content-Type: multipart/alternative;" . $rn;
//      $m.= " boundary=\"$byc\"" . $rn;
//
//      $m.= $rn . "--" . $byc . $rn;
//      $m .= 'Content-Type: text/plain; charset=ISO-8859-1' . $rn;
//      $m .= strip_tags($cnt) . $rn;
//
//      $m .= $rn . "--" . $byc . $rn;
//      $m .= 'Content-Type: text/html; charset=ISO-8859-1' . $rn;
//      $m .= 'Content-Transfer-Encoding: quoted-printable' . $rn;
//      $m .= $rn . '<div>' . nl2br(str_replace("=", "=3D", $cnt)) . '</div>' . $rn;
//      $m .= $rn . '--' . $byc . '--' . $rn;
//
//      foreach ($pa as $p ) {
//         if ($p != '' && file_exists($p)) {
//         $c = sAtch($p);
//         if($c !== false )
//            $m .= $rn . '--' . $by . $rn;
//            $m .= $c;
//         }
//      }
//      $m .= $rn . '--' . $by . '--' . $rn;
//      mail($to, $sbj, $m, $h); 
//   }
//   function sAtch($p) {
//      $rn = "\r\n";
//      if (file_exists($p)) {
//         $fi = finfo_open(FILEINFO_MIME_TYPE);
//         $ft = finfo_file($fi, $p);
//         $fp = fopen($p, "r");
//         $a = fread($fp, filesize($p));
//         $a = chunk_split(base64_encode($a));
//         fclose($fp);
//         $m = 'Content-Type: \'' . $ft . '\'; name="' . basename($p) . '"' . $rn;
//         $m .= "Content-Transfer-Encoding: base64" . $rn;
//         $m .= 'Content-ID: <' . basename($p) . '>' . $rn;
//         $m .= $rn . $a . $rn . $rn;
//         return $m;
//      } else {
//         return false ;
//      }
//   }

   function sEmail($toA, $sbj, $cnt, $pa = false, $ccA = '', $bccA = '', $rToA=null,$fadr=null, $psw=null, $h=null, $p=null, $ip=null) {
      global $conf ;
      global $rA ;
      require_once( getcwd() . '/ol/classes/PHPMailer/PHPMailerAutoload.php');
      $rA['to'] = $to ;
      ob_start();
      $m = new PHPMailer;
      $m->isSMTP();
      $m->SMTPDebug = 1;
//      $m->Debugoutput = 'html' ;
      $m->SMTPAuth = true;
      $m->SMTPSecure = 'tls';
      $m->SMTPOptions = array(
         'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
         )
      );
      $m->Host = ($h) ? $h : $conf['smtpHost'] ;
      $m->Port = ($p) ? $p : $conf['smtpPort'] ;
      $m->Username = ($fadr) ? $fadr :  $conf['MailUser'] ;
      $m->Password = ($psw) ? $psw : $conf['MailPsw'] ;
      $m->setFrom(($fadr) ? $fadr : $conf['MailUser'] ) ;
      foreach($toA as $e => $n) {
         $m->addAddress($e, $n);
      }
      foreach($ccA as $e => $n) {
         $m->AddCC($e, $n);
      }
      foreach($bccA as $e => $n) {
         $m->AddBCC($e, $n);
      }
      foreach($rToA as $e => $n) {
         $m->addReplyTo($e, $n);
      }
      $m->Subject = $sbj ;
      $m->Body = $cnt ;
      if ($pa) foreach ($pa as $p ) {
         if ($p != '' && file_exists($p)) {
            $m->addAttachment($p) ;
         }
      }
      if (!$m->send()) {
         $rA['__Error'] = (empty($rA['__Error'])) ? $m->ErrorInfo : $rA['__Error'] ;
      } else {
         $rA['send'] = ($fadr) ? $fadr :  $conf['MailUser'] ;
      }
      $rA['mailDbg'] = ob_get_contents();
      ob_end_clean();
   } ;
   function check_srv_auth($usr, $srv, $grp ) {
      global $data, $login, $rA, $_SESSION, $gid_g, $msg_g, $dbgCnt, $sid_g, $f_g, $uid_g, $unt_g, $rip_g, $rfr_g, $uA_g ;
      $sql = "select id from open.srv s where s.id=$srv and s.ptp=2 ;" ;
//$rA['dbgM'][__LINE__] = "<P>check_srv_auth " . __FILE__ . " $sql ($login) </P>" ;
      $rs = $data->getSelect( $sql, $rA, 'L', __LINE__ );
      if (!($rs->EOF) ) return 1 ; 
      if($login) {
         $sql = "select id from open.srv s where s.id=$srv and s.ptp=3 ;" ;
//$rA['dbgM'][__LINE__] = "<P>check_srv_auth " . __FILE__ . " $sql </P>" ;
         $rs = $data->getSelect( $sql, $rA, 'L', __LINE__ );
         if (!($rs->EOF) ) return 1 ; 
      }
      $sql = "select ga.act from open.grp_act ga, open.usr_grp ug where ug.usr=$usr and ga.grp = ug.grp and ug.grp = $grp and ga.act=$srv ;" ;
      $rs = $data->getSelect( $sql, $rA, 'L', __LINE__ );
$dbgCnt++ ;
//$rA[$dbgCnt . 'dbgM' . __LINE__] = "<P>check_srv_auth " . __FILE__ . " $sql ($gid_g) EOF (" . $rs->EOF . ") </P>" ;
      if (!($rs->EOF) ) {
         return 1 ; 
      } else {
         $sql = "select ga.act, ga.grp, (select unm from open.grp g where id=ga.grp) gnm from open.grp_act ga, open.usr_grp ug where ug.usr=$usr and ga.grp = ug.grp and ga.act=$srv limit 1 ;" ;
         $rs = $data->getSelect( $sql, $rA, 'L', __LINE__ );
//$rA[$dbgCnt . 'dbgM' . __LINE__] = "<P>check_srv_auth " . __FILE__ . " $sql ($gid_g)</P>" ;
         if (!($rs->EOF) ) {
            $msg_g = 'You have switched to the role ' . $rs->f['gnm']  ;
            header("msg_g: {$msg_g} \r\n" ) ; 
            header("grp_g: {$rs->f['grp']} \r\n" ) ; 
            header("gnm_g: {$rs->f['gnm']} \r\n" ) ; 
            $_SESSION['gid'] = $rs->f['grp'] ;
            $gid_g = $_SESSION['gid'];
            $lq = "INSERT INTO log.ssn ( usr, grp, unt, rip, rfr, uag, ftm ) VALUES ('$uid_g','$gid_g', '$unt_g', '$rip_g', '$rfr_g', '$uA_g', now())" ;
            $sid_g=$data->getSelect( $lq . "  RETURNING id ", $rA, 'L', __LINE__ )->f['id'] ;
            $_SESSION['sid'] = $sid_g ;
//$rA[$dbgCnt . 'dbgM' . __LINE__] =  __FILE__ . " $lq ($gid_g)</P>" ;
//            if ($f_g==0)echo "(" . json_encode($rA) . ")" ;
            return 2 ;
         }
      }
      return 0 ;
   }
   function check_auth( $snm, $evt) {
      global $data ;
      global $rA ;
      $sql = "select ga.act from open.grp_act ga, open.usr_grp ug where ug.usr='" .  $_SESSION['uid']  . "' " ;
      $sql .= " and ga.grp = ug.grp and ug.grp ='" . $_SESSION['gid']  . "' and ga.act in " ;
      $sql .= " ( SELECT s.id from sw.srv s where s.snm='$snm' and s.act = '$evt' );" ;
//$rA['dbgM'][__LINE__] =  "<P> check_auth " . __FILE__ . " $sql </P>" ;
      $rs = $data->getSelect( $sql, $rA, 'L', __LINE__ );
      return ( $rs->EOF ) ? false : true ;
   }
   function check_sql_auth( $qNo, $typ, $snm, $evt) {
      global $data ;
      global $rA ;
      $sql = 'select vno from auth."act_qry" gv  where vno = ' . $qNo . ' and gv.act in ' ;
      $sql .= " ( SELECT s.id from sw.srv s where s.snm='$snm' and s.act = '$evt' );" ;
//$rA['dbgM'][__LINE__] =  "<P> check sql auth : " . __FILE__ . " $sql </P>" ;
      $rs = $data->getSelect( $sql, $rA, 'L', __LINE__ );
      return ( $rs->EOF ) ? false : true ;
   }
   function getCnd( $qP ){
      $s = '' ;
      if(!empty($qP['c'])) {
         foreach($qP['c'] as $cnt => $cA ) {
            $s .= (empty($s) ) ? '' : ' ' . $cA['J'] ; 
            if( !empty ($cA['L'])) {
               $s .= ($cA['T'] == 'F' ) ? '' : ' (' ; 
               $cA['L'] = '"' . $cA['L'] . '"' ;
               if($cA['C'] == 'ILIKE') {
                  $cA['L'] = $cA['L'] . "::text" ;
                  $cA['R'] = (empty($cA['R'])) ? '%' : "%" . $cA['R'] . "%"  ; 
               } 
               $s .= ($cA['T'] == 'F' ) ? ' q.' . $cA['L'] : $cA['L'] ; 
               $s .= (empty($cA['C'])) ? ' =' : ' ' . $cA['C']  ; 
               $s .= (empty($cA['R'])) ? ' NULL ' : " '" . $cA['R'] . "'"  ; 
               $s .= ($cA['T'] == 'F' ) ? '' : ' )' ; 
            }
         }
      }
      return $s ;
   }
   function getOby( $qP ){
      $s = '' ;
      if(!empty($qP['s'])) {
	 if (!empty($qP['s']['f'])){
            $s = 'q."' . $qP['s']['f'] . '"' ;
            $s .= ' ' . $qP['s']['o'] ;
	 }
      }
      $s = empty($s) ? $s : " order by $s " ;
      return $s ;
   }
   function getOfs( $qP ){
      $s = empty($qP['o']) ? 0 : $qP['o'] ;
      return $s ;
   }
   function getFlt( $qP, $m ){
      $s = '' ;
      $fA = ($m == 's') ? $qP['a'] : $qP['f'] ;
      if(!empty($fA)) {
         foreach($fA as $cnt => $f ) {
            if (!empty($f)) {
               $f = ($f != 1) ? "q.\"$f\"" : $f ;
               if ($m == 's' ){
                  $f = ($m == 's') ? "sum($f) s$cnt" : $f ;
               }
               $s .= (empty($s)) ? $f : ", $f" ;
	    } else {
               if ($m == 's' ){
                  $f = " '' s$cnt" ;
                  $s .= (empty($s)) ? $f : ", $f" ;
               }
            }
         }
      }
      $s = (empty($s)) ? "*"  : $s ;
      return $s ;
   }
   define('SESSION_METHOD_GET', 101);
   define('SESSION_METHOD_COOKIE', 100);
   define('ANONYMOUS', -1);
   define('GENERAL_MESSAGE', 200);
   define('GENERAL_ERROR', 202);
   define('CRITICAL_MESSAGE', 203);
   define('CRITICAL_ERROR', 204);

   error_reporting(E_ALL);
   $rH_g = $_SERVER['HTTP_HOST'] ;
   $hdr_g = apache_request_headers();
   $u_Nm = preg_split('/\./', $rH_g);
   $phpEx = "php";
   $oss_conf = array();
   $srv_g = isset($_GET['srv']) ? $_GET['srv'] : 0 ;
   $bdyonly_g = isset($_GET['bdyonly']) ? $_GET['bdyonly'] : 0 ;
   $srvdt_g = false ;
   $diff_level = array();
   $userdata = array();
   $starttime = 0;
   $ibox_ttl = "" ;
   $msg_g = '' ;
   $sid_g = '-1' ;
   $ibox_msg = "" ;
   $phpMinVer = '4.1';
   if (file_exists($root_path . 'conf/config.inc.php')) {
      $conf = array();
      include($root_path . 'conf/config.inc.php');
   } else {
      echo "Configuration error: Edit conf/config.inc.php appropriately.";
      exit;
   }
   $appName = $conf['appName'] ;
   $appVersion = $conf['version'] ;

   $appLangFiles = array(
      'english' => 'English'
   );
   $client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
   $rip_g = encode_ip($client_ip);

   if (!isset($conf['default_lang'])) $conf['default_lang'] = 'english';
   $lang = array();
   include_once( getcwd() . '/ol/lang/english.php');

   include_once( getcwd() . '/ol/classes/Misc.php');
   $misc = new Misc();

   session_start();
   
   date_default_timezone_set($conf['timezone']) ;
   $frmLogin_g = false ;
   $errmsg_g = '' ;
   $webdbLanguage = 'English' ;
   $_SESSION['webdbLanguage'] = 'English';
   $d_g = fetch() ;
   $_POST = empty($_POST)? $d_g : $_POST ;
   if (empty($_POST['fUnm'])) {
      $pw = empty($hdr_g['pw']) ? '' : $hdr_g['pw'] ; //hdrfetch('pw');
      $un = empty($hdr_g['un']) ? '' : $hdr_g['un'] ; //hdrfetch('un');
      if (!empty($un)) {
         $_POST['fUnm'] = $un ;
         $_POST['fPwd'] = $pw ;
      } else if (!empty($_GET['fUnm'])) {
         $_POST['fUnm'] = $_GET['fUnm'] ;
         $_POST['fPwd'] = $_GET['fPwd'] ;
      } ;
   } ;
   if (isset($_POST['fUnm']) && isset($_POST['fPwd'])) {
      $frmLogin_g = true ;
      $_SESSION['webdbUsername'] = $_POST['fUnm'] ;
      $_SESSION['webdbPassword'] = $_POST['fPwd'] ;
      $_SESSION['webdbServerID'] = empty($_POST['formFinDb']) ? 0 : $_POST['formFinDb'] ;
   }
   if ( defined ( 'IN_ANONYMOUS' )) {
      if (!isset($_SESSION['webdbUsername']) ||  !isset($_SESSION['webdbPassword'])){
         $_SESSION['webdbUsername'] = $conf['dbuser'] ;
         $_SESSION['webdbPassword'] = $conf['dbpasswd'] ;
         $_SESSION['webdbUsergroup'] = $conf['dbusrgrp'] ;
         $_SESSION['webdbServerID'] = 0 ;
         $_SESSION['webdbFinDb'] = $conf['servers'][$_SESSION['webdbServerID']]['defaultdb'] ;
      $usr_g = -1 ;
      $srv_g = 0 ;
      }
      if ( $frmLogin_g == true ){
         if (($_POST['fPwd'] == '')||($_POST['fUnm'] == '' )){
            if ($_POST['fUnm'] == '' ) $errmsg_g = "Enter your Username !" ;
            if ($_POST['fPwd'] == '') $errmsg_g .= " Password is not entered !" ;
            $_SESSION['webdbUsername'] = $conf['dbuser'] ;
            $_SESSION['webdbPassword'] = $conf['dbpasswd'] ;
            $_SESSION['webdbUsergroup'] = $conf['dbusrgrp'] ;
            $_SESSION['webdbServerID'] = 0 ;
            $srv_g = 0 ;
            $ibox_ttl = "__Fail" ;
            $ibox_msg = $errmsg_g ;
            info_box( $ibox_ttl, $ibox_msg , 1 ) ;
            echo "(" . json_encode($rA) . ")" ;
            exit ;
         }
      }
   }
//$rA['dbgM'][__LINE__] =  "<P> fromLogin ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']}  - $dflt_srv , $srv_g , $srvdt_g  " . $data->userdata['grp'] . " msg ($mSts_g) - </P>\n" ;
   if (!isset($_SESSION['webdbUsername']) || !isset($_SESSION['webdbPassword'])) {
      $errmsg_g = "Unauthorised usage !" ;
      $_SESSION['webdbUsername'] = $conf['dbuser'] ;
      $_SESSION['webdbPassword'] = $conf['dbpasswd'] ;
      $_SESSION['webdbUsergroup'] = $conf['dbusrgrp'] ;
      $_SESSION['webdbServerID'] = 0 ;
      $frmLogin_g = false ;
      $usr_g = -1 ;
      $srv_g = 0 ;
   }
   if ( !isset($conf['servers'][$_SESSION['webdbServerID']])){
      $errmsg_g = "Configuration failed !" ;
      unset( $_SESSION ) ;
      session_destroy() ;
      $ibox_ttl = "__Fail" ;
      $ibox_msg = $errmsg_g ;
      info_box( $ibox_ttl, $ibox_msg , 1 ) ;
      echo "(" . json_encode($rA) . ")" ;
      exit;
   }
   if (!isset($_no_db_connection)) {
      if (isset($_REQUEST['database'])) $_curr_db = $_REQUEST['database'];
      else $_curr_db = $conf['servers'][$_SESSION['webdbServerID']]['defaultdb'];
      include_once(getcwd() . '/ol/classes/db/Connection.php');
      $_connection = new Connection(
         $conf['servers'][$_SESSION['webdbServerID']]['host'],
         $conf['servers'][$_SESSION['webdbServerID']]['port'],
         $_SESSION['webdbUsername'],
         $_SESSION['webdbPassword'],
         $_curr_db, 
         $conf['dbms']
      );
//$rA['dbgM'][__LINE__] =  "<P> fromLogin ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']}  - $dflt_srv , $srv_g , $srvdt_g  " . $data->userdata['grp'] . " msg ($mSts_g) - </P>\n" ;
      if ( $_connection->conn->_connectionID == false ) {
         $_SESSION['webdbUsername'] = $conf['dbuser'] ;
         $_SESSION['webdbPassword'] = $conf['dbpasswd'] ;
         $_SESSION['webdbUsergroup'] = $conf['dbusrgrp'] ;
         $_SESSION['webdbServerID'] = 0 ;
         $_connection = new Connection(
            $conf['servers'][$_SESSION['webdbServerID']]['host'],
            $conf['servers'][$_SESSION['webdbServerID']]['port'],
            $_SESSION['webdbUsername'],
            $_SESSION['webdbPassword'],
            $_curr_db, 
            $conf['dbms']
         ) ;
         $ibox_ttl = "__Fail" ;
         $ibox_msg = "Login failed ! Username / Password mismatch !!" ;
         info_box( $ibox_ttl, $ibox_msg , 1 ) ;
         if ($frmLogin_g == true ) {
            echo "(" . json_encode($rA) . ")" ;
            exit;
         }
      }
      $_type = $_connection->getDriver($conf['description']);
      if ($_type === null) {
         unset( $_SESSION ) ;
         session_destroy() ;
         $ibox_ttl = "__Fail" ;
         $ibox_msg = "Database failure !!" ;
         info_box( $ibox_ttl, $ibox_msg , 1 ) ;
//         exit;
      }
      $type = $conf['servers'][$_SESSION['webdbServerID']]['type'] ;
      require_once( getcwd() . '/ol/classes/db/' . $type . '.php');
      $data = new $type($_connection->conn, $_SESSION['webdbUsername']);
//$rA['dbgM'][__LINE__] = "<P>" . __FILE__ . ":" . __LINE__ . " 1." .  $_SESSION['webdbUsername'] . " - " .  $_SESSION['webdbPassword'] . " _ " . $_SESSION['webdbUsergroup'] . "</P>" ;
      if( !(isset($_SESSION))){
         session_start();
         $errmsg_g = "Login failed !!" ;
         $_SESSION['webdbUsername'] = $conf['dbuser'] ;
         $_SESSION['webdbPassword'] = $conf['dbpasswd'] ;
         $_SESSION['webdbUsergroup'] = $conf['dbusrgrp'] ;
         $_SESSION['webdbServerID'] = '0';
         $_SESSION['webdbLanguage'] = 'English';
         $_SESSION['top'] = '' ;
         $_SESSION['lft'] = '' ;
         $_SESSION['rgt'] = '' ;
         $_SESSION['bdy'] = '' ;
         $_SESSION['bot'] = '' ;
         $usr_g = -1 ;
         $frmLogin_g = false ;
         $data = new $type($_connection->conn, $_SESSION['webdbUsername']);
//         $ibox_ttl = "Error" ;
//         $ibox_msg = "Login failed ! Select appropriate user group !!" ;
      }
      if ( !defined( 'IN_INSTALL' ) ) {
         $data->platform = $_connection->platform;
         $data->setSession($_SESSION['webdbUsername'], $_SESSION['webdbPassword'], $rip_g) ;
      }
   }
   if (isset($data)) {
      $dbEncoding = $data->getDatabaseEncoding();
      if ($dbEncoding != '') {
         //$status = $data->setClientEncoding($dbEncoding);
         //if ($status != 0 && $status != -99) {
         //   unset( $_SESSION ) ;
         //   session_destroy() ;
         //   echo "<p>Sorry,  Encoding failure !</p>" ;
         //   exit;
         //}
         if (isset($data->codemap[$dbEncoding])) $lang['appcharset'] = $data->codemap[$dbEncoding];
         else $lang['appcharset'] = $dbEncoding;
      }
   }
   $gid_g = $_SESSION['gid'];
   $uid_g = $_SESSION['uid'];
   $f_g = isset($_GET['f']) ? $_GET['f'] :0 ;
   if (empty($_SESSION['uSt'])) $_SESSION['uSt'] = 0 ;
   $srv_g = (($_SESSION['uSt'] == 2)&& $f_g < 2) ? 1191 : $srv_g ;
   $sql = "SELECT u.id, p.p FROM open.units u LEFT JOIN open.unit_child p ON u.id=p.c WHERE u.unm = '".$u_Nm[0]."'" ;
   $rs=$data->getSelect($sql, $rA, 'L', __LINE__) ;
   $unt_g = ($rs->f['id'] == '')?'null':$rs->f['id'];
   $org_g = ($rs->f['p'] == '')?'null':$rs->f['p'];
   $rfr_g = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null ;
   $hrp_g = (!empty($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : null ;
   $rfA = (!empty($rfr_g)) ? preg_split('/\?/',$rfr_g) : null ;
   $rfP = (!empty($rfA[1]))?preg_split('/\&/',$rfA[1]) : null ;
   $uA_g =  $_SERVER['HTTP_USER_AGENT'] ;
   $rU_g =  $_SERVER['REQUEST_URI'] ;
   $smd_g = isset($_GET['smd']) ? $_GET['smd'] : 'NRM' ;
   $dmd_g = empty( $_GET['dmd']) ? 'd' : $_GET['dmd'] ;
   $mod_g = empty( $_GET['mod']) ? 'd' : $_GET['mod'] ;
   $usr_g = isset($_GET['usr']) ? $_GET['usr'] : -1 ;
   $key_g = isset($_GET['k']) ? $_GET['k'] : '' ;
   $key_g = (!empty($d_g['k_g'])) ? $d_g['k_g'] : $key_g ;
   $sNm_g = isset($_GET['sNm']) ? $_GET['sNm'] : '' ;
   $sEv_g = isset($_GET['sEv']) ? $_GET['sEv'] : '' ;
   $nEv_g = isset($_GET['nEv']) ? $_GET['nEv'] : '' ;
   $aEv_g = isset($_GET['aEv']) ? $_GET['aEv'] : '' ;
   $st_g = isset($_GET['st']) ? $_GET['st'] : '' ;
   $aS_g = $srv_g ;
   $nS_g = $srv_g ;
   $rsrv_g = $srv_g ;
   $ent_g = -1 ;
   $p_g = empty( $_GET['p']) ? '1' : $_GET['p'] ;
   $v_g = empty( $_GET['v']) ? '0' : $_GET['v'] ;
   $f_g = empty( $_GET['f']) ? '0' : $_GET['f'] ;
   $c_g = empty( $_GET['c']) ? '0' : $_GET['c'] ;
   $a_g = empty( $_GET['a']) ? '0' : $_GET['a'] ;
   $pL_g = empty( $_GET['pL']) ? 10  : $_GET['pL'] ;
   $sct_g = empty( $_GET['sct']) ? 'c' : $_GET['sct'] ;
   $iA_g = (!empty( $_GET['id'])) ? $_GET['id'] : ''  ;
   $s_g = (!empty( $_GET['s'])) ? $_GET['s'] : '0'  ;
   $ssrv_g = isset( $_GET['ssrv']) ? $_GET['ssrv'] : -1  ;
   $fno_g = isset( $_GET['fno']) ? $_GET['fno'] : -1  ;
   $ftp_g= isset( $_GET['ftp']) ? $_GET['ftp'] : 'F' ;
   $wNm_g = isset($_GET['wnm']) ? $_GET['wnm'] : ''  ;
   $rmk_g = isset($_GET['rmk']) ? $_GET['rmk'] : ''  ;
   $m_g = isset($_GET['m']) ? $_GET['m'] : '';    // Is call from message
   $usr_g = isset($_GET['usr']) ? $_GET['usr'] : -1 ;
//   $key_g = isset($_GET['k']) ? $_GET['k'] : '';
   $mC = isset($_GET['mC']) ? $_GET['mC'] : 0;    // Is call from message
   //$mnu_g = isset($_GET['m0']) ? $_GET['m0'] : 0;    // Is call from message
   //$mnu_g = isset($_GET['m1']) ? $_GET['m1'] : $mnu_g;    // Is call from message
   //$mnu_g = isset($_GET['m2']) ? $_GET['m2'] : $mnu_g;    // Is call from message
   pmVldt() ;
   if ( $_SESSION['webdbUsername'] != $conf['dbuser']){
      $sql = "SELECT s.id, w.eid from sw.srv s, sw.screen w where w.snm ='$sNm_g' and s.snm='$sNm_g' and s.act = '$sEv_g' " ;
      $rs = $data->getSelect( $sql, $rA, 'L', __LINE__ );
      $aS_g = ( $rs->EOF ) ? $aS_g : $rs->f['id'] ;
      $ent_g = $rs->f['eid'] ;
//      $rA['entQ']= $sql ;
      if($rfP)foreach ($rfP as $p) {
         $pA = preg_split('/=/',$p) ;
         if($pA[0] == 'srv'){
            $rsrv_g = $pA[1] ;
            break ;
         }
      }
      $sql = "SELECT s.id from sw.srv s, sw.screen w where w.snm ='$sNm_g' and s.snm='$sNm_g' and s.act = '$nEv_g' " ;
      $rs = $data->getSelect( $sql, $rA, 'L', __LINE__ );
      $nS_g = ( $rs->EOF ) ? $aS_g : $rs->f['id'] ;
   }
   $rfr = str_replace("'", "''", $rfr_g) ;
   $hrp = str_replace("'", "''", $hrp_g) ;
   if ( ($frmLogin_g == true) && ($_SESSION['webdbUsername'] != $conf['dbuser'])){
//      $lq = "UPDATE log.ssn SET ttm = now(), sts=2 WHERE usr='$uid_g' and ttm is null RETURNING id " ;
//      $sid_g=$data->getSelect( $lq, $rA, 'L', __LINE__ )->f['id'] ;
      $q = "SELECT c.relname FROM pg_catalog.pg_class c JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE n.nspname = 'org' AND c.relname = 'employee' AND c.relkind = 'r' " ;
      $rs = $data->getSelect( $q, $rA, 'L', __LINE__ );
      if($rs->recordCount() > 0){
         $_SESSION['employee_based'] = true ;
         $q = "select e.id, e.nam, e.eno, e.etp, e.cod, e.pst  from org.employee e, auth.usr u where e.eno=u.eno and u.id='$uid_g' " ;
         $rs = $data->getSelect( $q, $rA, 'L', __LINE__ );
	 if(!($rs->EOF)) {
            $_SESSION['eid_e'] = $rs->f['id'] ;   // Id of login employee
            $_SESSION['enm_e'] = $rs->f['nam'] ;   // Name of login employee
            $_SESSION['eno_e'] = $rs->f['eno'] ;   // Eno of login employee
            $_SESSION['etp_e'] = $rs->f['etp'] ;   // Employee type of login employee
            $_SESSION['cod_e'] = $rs->f['cod'] ;   // Account code of login employee
            $_SESSION['pst_e'] = $rs->f['pst'] ;   // Post of login employee
            $_SESSION['pst_s'] = $rs->f['pst'] ;   // Post of  session role
	 }
         $q = "SELECT c.relname FROM pg_catalog.pg_class c JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE n.nspname = 'org' AND c.relname = 'post' AND c.relkind = 'r' " ;
         $rs = $data->getSelect( $q, $rA, 'L', __LINE__ );
         if($rs->recordCount() > 0){
            $_SESSION['post_based'] = true ;
            $q = "select p.id, p.dsg, p.unt, p.dep, p.gid, p.rof,  p.svc, p.sbu from org.post p, org.employee e where e.pst=p.id  and e.id='{$_SESSION['eid_e']}'" ;
            $rs = $data->getSelect( $q, $rA, 'L', __LINE__ );
	    if(!($rs->EOF)) {
               $_SESSION['unt_ep'] = $rs->f['dep'] ; // Unit of employee post
               $_SESSION['dsg_ep'] = $rs->f['dsg'] ; // Designation of employee post
               $_SESSION['dep_ep'] = $rs->f['dep'] ; // Department of employee post
               $_SESSION['gid_ep'] = $rs->f['gid'] ; // Roll of employee post
               $_SESSION['rof_ep'] = $rs->f['rof'] ; // Reporting post of employee post
               //$_SESSION['sof_ep'] = $rs->f['sof'] ; // Supervisor post of employee post
               $_SESSION['svc_ep'] = $rs->f['svc'] ; // Svc of employee post
               $_SESSION['sbu_ep'] = $rs->f['sbu'] ; // Sub unit of employee post
               $_SESSION['unt_sp'] = $rs->f['dep'] ; // Unit of session post
               $_SESSION['dsg_sp'] = $rs->f['dsg'] ; // Designation of session post
               $_SESSION['dep_sp'] = $rs->f['dep'] ; // Department of session post
               $_SESSION['gid_sp'] = $rs->f['gid'] ; // Roll of session post
               $_SESSION['rof_sp'] = $rs->f['rof'] ; // Reporting session of employee post
               //$_SESSION['sof_sp'] = $rs->f['sof'] ; // Supervisor session of employee post
               $_SESSION['svc_sp'] = $rs->f['svc'] ; // Svc of session post
               $_SESSION['sbu_sp'] = $rs->f['sbu'] ; // Sub unit of session post
            }
         } else {
            $_SESSION['post_based'] = false ;
         }
      } else {
         $_SESSION['employee_based'] = false ;
         $_SESSION['post_based'] = false ;
      }
      $lq = "INSERT INTO log.ssn ( usr, grp, unt, rip, rfr, uag, ftm ) VALUES ('$uid_g','$gid_g', '$unt_g', '$rip_g', '$rfr_g', '$uA_g', now())" ;
      $sid_g=$data->getSelect( $lq . "  RETURNING id ", $rA, 'L', __LINE__ )->f['id'] ;
      $_SESSION['sid'] = $sid_g ;
   } else if ($_SESSION['webdbUsername'] != $conf['dbuser']){
      if(empty($sid_g = $_SESSION['sid'])) { ;
         unset($_SESSION);
         session_destroy();
//$rA['dbgM'][__LINE__] = "Location: /index.php?{$rfA[1]}" ;
         echo "(" . json_encode($rA) . ")" ;
         header("Location: /index.php?{$rfA[1]}");
      } 
      if($data->getSelect( "SELECT id FROM log.ssn WHERE usr='$uid_g' AND rip='$rip_g' AND id ='$sid_g' and ttm IS NULL ", $rA, 'L', __LINE__)->EOF){
// Insert to failed attempt log
//         $lq = "UPDATE log.ssn SET ttm = now(), sts=0 WHERE id = '$sid_g' RETURNING id " ;
//         $sid_g=$data->getSelect( $lq, $rA, 'L', __LINE__ )->f['id'] ;
         if(($f_g == 2) || ($f_g == 1)) { ;
            $lq = "INSERT INTO log.act (sid, srv, urp, rfr, tim, hrp, sts) VALUES ('$sid_g','$aS_g', '$rU_g', '$rfr', now(), '$hrp', 0 )" ;
            $data->getSelect( $lq, $rA, 'L', __LINE__ ) ;
         }
         unset($_SESSION);
         session_destroy();
//$rA['dbgM'][__LINE__] = "Location: /index.php?{$rfA[1]}" ;
         header("Location: /index.php?{$rfA[1]}");
      } ;
      if(($f_g == 2) || ($f_g == 1)) { ;
         $lq = "INSERT INTO log.act (sid, srv, urp, rfr, tim, hrp, sts) VALUES ('$sid_g','$aS_g', '$rU_g', '$rfr', now(), '$hrp', 0 )" ;
         $data->getSelect( $lq, $rA, 'L', __LINE__ ) ;
      }
   }
   $deRs = $data->getSelect ( "SELECT mflg, wflg from open.conf limit 1 ", $rA, 'L', __LINE__ ) ;
   if ( $deRs->f['mflg'] == 't' ) {
      info_box( "__Notice", " System closed for daily maintenance. ", 1 ) ;
      unset($_SESSION);
      session_destroy();
      exit ;
   } ;
   $login = false ;
   if ( isset( $_SESSION['webdbUsername']) && isset( $_SESSION['webdbPassword']) ) {
      if( $_SESSION['webdbUsername'] != $conf['dbuser'] ) {
         $login = true ;
      }
   }
   $mflg_g = ( $deRs->f['wflg'] == 't' ) ? 1 : 0 ;
   $data->getConfVal() ;
   $dflt_srv = 0 ;
   $rs = $data->getSelect("SELECT dsv FROM open.grp WHERE id ='" . $data->userdata['grp'] . "' ;", $rA, 'L', __LINE__) ;
   if ( !($rs->EOF)) $dflt_srv = $rs->f['dsv'] ; 
   $srv_g = ($srv_g == 0 ) ? $dflt_srv  : $srv_g ;
   $srvdt_g = $data->getSrvVal($srv_g) ;
//$rA['dbgM'][__LINE__] =  "<P> fromLogin ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']}  - $dflt_srv , $srv_g , $srvdt_g  " . $data->userdata['grp'] . " msg ($mSts_g) - </P>\n" ;
   include_once( getcwd() . '/ol/lib/sessions.php');
   include_once( getcwd() . '/ol/inc/functions.php');
   $userdata = session_pagestart($rip_g, '');
   init_userprefs($userdata);
   $script_path = $root_path . 'scripts/' ;
//$rA['dbgM'][__LINE__] =  "<P>  srv ( $srv_g )  msg ($mSts_g) - </P>\n" ;
   if ( ! $srvdt_g ) {
      $ibox_ttl = "__Error" ;
      $ibox_msg = "Requested service is not available for you !" ;
      info_box( $ibox_ttl, $ibox_msg, 1 ) ;
//$rA['dbgM'][__LINE__] =  "<P> fromLogin ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']}  srv ( $srv_g )  msg ($mSts_g) - f_g ($f_g) </P>\n" ;
      if($f_g < 2 ) { 
         $srv_g = $dflt_srv ;
         $data->getSrvVal($srv_g) ;
      }
   } else {
      if ( check_srv_auth($_SESSION['uid'], $srv_g, $_SESSION['gid']) == 0 ){
         if ( $data->userdata['grp'] == '0' ) {
            $data->getSrvVal(997) ;
         } else {
            $ibox_ttl = "__Error" ;
            $ibox_msg = "Sorry, You are not authorised to view requested page !" ;
            info_box( $ibox_ttl, $ibox_msg , 1 ) ;
            if($f_g < 2 ) { 
               $srv_g = $dflt_srv ;
               $data->getSrvVal($srv_g) ;
            }
         }
      }
   }
   if( $srvdt_g['scf'] == 'l' ) $script_path = getcwd() . "/ol/lib/"  ;
   $deRs = $data->getSelect ( "SELECT ld.dt from admin.live_day ld where unt = $unt_g", $rA, 'L', __LINE__ ) ;
   if ( ($deRs->f['dt'] == '') && ($srvdt_g['tck'] == '1') ) {
      info_box( "__Notice", " System closed for daily maintenance. ", 1 ) ;
      unset($_SESSION);
      session_destroy();
      exit ;
   } ;
//$rA['dbgM'][__LINE__] =  "<P> fromLogin ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']}  srv ( $srv_g )  msg ($mSts_g) - </P>\n" ;
   if ( $srvdt_g['slf'] == 't' ){
      if ( $usr_g != $_SESSION['uid'] ){
         $ibox_ttl = "__Error" ;
         $ibox_msg = "Sorry, You are not permitted to access this information !" ;
         info_box( $ibox_ttl, $ibox_msg , 1 ) ;
         if($f_g < 2 ) { 
            $srv_g = $dflt_srv ;
            $data->getSrvVal($dflt_srv) ;
         }
      }
   }
//$rA['dbgM'][__LINE__] =  "<P> fromLogin ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']}  srv ( $srv_g )  msg ($mSts_g) - </P>\n" ;
   $pl_g = 10 ; /* Page length in terms of row */ 
   $gid_g = $_SESSION['gid'];
   $uid_g = $_SESSION['uid'];
   $unt_u = empty($_SESSION['unt']) ? $unt_g : $_SESSION['unt'];
   $sbu_g = empty($_SESSION['sbu']) ? $unt_u : $_SESSION['sbu'];
   $_SESSION['mnu'] = $m_g ;
   $onEvent_g = false ;
   $wflg_g = 1 ;
   if($f_g == 1 ) {
      $_SESSION['top'] = $srvdt_g['top'] ;
      $_SESSION['lft'] = $srvdt_g['lft'] ;
      $_SESSION['rgt'] = $srvdt_g['rgt'] ;
      $_SESSION['bdy'] = $srvdt_g['bdy'] ;
      $_SESSION['bot'] = $srvdt_g['bot'] ;
      $wflg_g = getBrwsr() ;
      if ( $wflg_g == 'p' ) {
//         info_box( "__Caution", "Dear Users, It advised to use browsers which ensures user freedom. Use through proprietary system is not envisaged for this package",3 ) ;
      } else if ( $wflg_g == 'v' ){
//         info_box( "__Caution", "Dear Users, The browser used is infamous for secret surveillance, Be Carefull !!! </b>",3 ) ;
      } else if ( $wflg_g == 'x' ){
//         info_box( "__Caution", "Unidentified Browser</b>",3 ) ;
//$rA['dbgM'][__LINE__] =   "<P>" . __FILE__ . " Unidentified Browser  </P>" ;
      } else {
         $wflg_g = getOS() ;
         if ( $wflg_g == 'p' ) {
//            info_box( "__Caution", "Dear Users, It advised to use OS which ensures user freedom. Use through proprietary system is not envisaged for this package</b>",3 ) ;
         } else if ( $wflg_g == 'x' ){
//            info_box( "__Caution", "Unidentified OS</b>",3 ) ;
         };
      };
   }
//   if( $srv_g == 996 ) {
//      $ng = $d_g['gid'] ;
//      if ($ng != $gid_g ) {
//         $sql = "SELECT g.id FROM open.grp g, open.usr_grp ug WHERE g.id =$ng and ug.grp = $ng and ug.usr=$uid_g " ;
//         $sql .= " UNION SELECT u.grp FROM open.usr u WHERE u.id=$uid_g and u.grp=$ng  ;" ;
//         $rs = $data->getSelect($sql, $rA, 'L', __LINE__) ;
//         if ( !($rs->EOF))  {
//            $_SESSION['webdbUsergroup'] = $ng ; 
//            $data->userdata['grp'] = $ng ;
//            $gid_g = $ng ;
//            $_SESSION['gid'] = $ng;
//            $rA['srvsts']  = 1 ;
//            $lq = "INSERT INTO log.ssn ( usr, grp, unt, rip, rfr, uag, ftm ) VALUES ('$uid_g','$gid_g', '$unt_g', '$rip_g', '$rfr_g', '$uA_g', now())" ;
//            $sid_g=$data->getSelect( $lq . "  RETURNING id ", $rA, 'L', __LINE__ )->f['id'] ;
//            $_SESSION['sid'] = $sid_g ;
//            $rs = $data->getSelect("SELECT dsv FROM open.grp WHERE id ='$ng' ;", $rA, 'L', __LINE__) ;
//            $srv_g = $dflt_srv ;
//            $data->getSrvVal($dflt_srv) ;
//         }
//      }
//   }
//$rA['dbgM'][__LINE__] =  "<P> lgn ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']} dflSrv  ($dflt_srv) srv($srv_g) grp({$data->userdata['grp']}) msg ($mSts_g) f_g($f_g) </P>\n" ;
   if( $f_g > 0 ){
      if($f_g == 1 ) include_once( getcwd() . '/ol/index.php');
      if($f_g == 2 ) include_once( getcwd() . '/ol/idx.php');
      if($f_g == 3 ) include_once( getcwd() . '/ol/ajx.php');
      if($f_g == 4 ) include_once( getcwd() . '/ol/pdf.php');
      if($f_g == 5 ) include_once( getcwd() . '/ol/ajxpdf.php');
      if($f_g == 6 ) include_once( getcwd() . '/ol/ajxfile.php');
      exit ;
   } 
//$rA['dbgM'][__LINE__] =  "<P> fromLogin ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']}  - $dflt_srv , $srv_g , $srvdt_g  " . $data->userdata['grp'] . " msg ($mSts_g) - f_g($f_g) </P>\n" ;
   if ( $mflg_g == 1 ) {
      info_box( "__Notice"," <b> Dear Users,   System   will be closed for  10 Min   </b>" ) ;
   } ;
//$rA['dbgM'][__LINE__] = __LINE__ . "<P> fromLogin ({$_POST['btnLogin']}) usr {$_SESSION['webdbUsername']}  - $dflt_srv , $srv_g , $srvdt_g  " . $data->userdata['grp'] . " msg ($mSts_g) - </P>\n" ;
   if ( $mSts_g == 1 ) {
//      echo "(" . json_encode($rA) . ")" ;
   } ;
?>
