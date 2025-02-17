<?php
   $p = empty( $_GET['p']) ? '1' : $_GET['p'] ;
//   $v = empty( $_GET['v']) ? '0' : $_GET['v'] ;
   $f = empty( $_GET['f']) ? '0' : $_GET['f'] ;
   $c = empty( $_GET['c']) ? '0' : $_GET['c'] ;
   $a = empty( $_GET['a']) ? '0' : $_GET['a'] ;
   $s = empty( $_GET['s']) ? null : $_GET['s'] ;
   $sc = empty( $c ) ? 'snm is NULL' : "snm = '$snm'" ;
//   $kV = empty( $_GET['kV']) ? '' : $_GET['kV'] ;
   $pL = empty( $_GET['pL']) ? 10  : $_GET['pL'] ;
   //$Cnd = empty( $_GET['Cnd']) ? '' : $_GET['Cnd'] ;
   //$oBy = empty( $_GET['oBy']) ? '' : $_GET['oBy'] ;
   //$flt = empty( $_GET['flt']) ? '*' : $_GET['flt'] ;
   //$mod = empty( $_GET['mod']) ? 'd' : $_GET['mod'] ;
   $sct = empty( $_GET['sct']) ? 'c' : $_GET['sct'] ;
   $sql = empty( $_GET['sql']) ? '' : $_GET['sql'] ;
   $qry = " SELECT str, ttl, did, obf, obo FROM sw.grp_view WHERE $s order by ord ;" ;
   $Cnd = getCnd( $d_g['qP_g']);
   $oBy = getOby( $d_g['qP_g']);
   $ofs = getOfs( $d_g['qP_g']);
   $flt = getFlt( $d_g['qP_g'], $sct);
   $rA['v_q']  = $qry ;
   $p = ( $p < 1 ) ? 1 : $p ;
   $rs = $data->getSelect( $qry, $rA, 'Dt', __LINE__  ) ;
   if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
   $rA['_qry']  = '';
   if ($rs->EOF) {
      if (empty($rs->f['str'])) continue ;
      if ($oBy == '') $oBy = ($rs->f['obf'] == '' ) ? '' : " order by " . $rs->f['obf'] . " " . $rs->f['obo'] ;
      if ($Cnd != '') $Cnd =  ' WHERE ' . $Cnd  ;
      $lmt = (($sct == 'f')||($sct == 'cf')||($sct == 'c')) ? " LIMIT 0 " : " " ; 
      $lmt = (strstr( $sct, 'p' ) && ($pL != 'h') ) ?  " OFFSET ($p - 1) * $pL LIMIT $pL " : $lmt ; 
      switch ( $mod ) {
         case 'p' :
            $rA['_qry']  = "SELECT * FROM (" . $rs->f['str'] . " ) q " . $Cnd . $oBy ;
            echo "(" . json_encode($rA) . ")" ;
//            return ;
            break ;
         case 'd' :
            $rA[1] = '' ;
            $rA[2] = '' ;
            $rA[3] = '' ;
//            $qry = $rs->f['str'] ;
            $dt  = fetch() ;
            $qry = getQryFrmPrimitive( $rs->f['str'], $dt )  ;
//            while(list($key, $sDt) = each($dt)) {
//               if ( $key == 'itemValidation' ) continue ;
//               $pat = "/'#". $key . "'/"  ;
//               $sDt = ($sDt == 'NULL')? $sDt : "'$sDt'" ;
//               $qry = preg_replace( $pat, $sDt, $qry ) ;
//            }
//            $pat = "/" . "'\$k_g'" . "/" ;
//            $qry = preg_replace( $pat, $key_g, $qry ) ;
//            $pat = "/" . '\$k_g' . "/" ;
//            $qry = preg_replace( $pat, $key_g, $qry ) ;
//            $pat = "/" . '\$sid_g' . "/" ;
//            $qry = preg_replace( $pat, $sid_g, $qry ) ;
//            $pat = "/" . '\$gid_g' . "/" ;
//            $qry = preg_replace( $pat, $gid_g, $qry ) ;
//            $pat = "/" . '\$uid_g' . "/" ;
//            $qry = preg_replace( $pat, $uid_g, $qry ) ;
//            $pat = "/" . '\$org_g' . "/" ;
//            $qry = preg_replace( $pat, $org_g, $qry ) ;
//            $pat = "/" . '\$unt_g' . "/" ;
//            $qry = preg_replace( $pat, $unt_g, $qry ) ;
//            $pat = "/" . '\$mnu' . "/" ;
//            $qry = preg_replace( $pat, $_SESSION['mnu'], $qry ) ;
//            $pat = "/" . '\$aS_g' . "/" ;
//            $qry = preg_replace( $pat, $aS_g, $qry ) ;
//            $pat = "/" . '\$rsrv_g' . "/" ;
//            $qry = preg_replace( $pat, $rsrv_g, $qry ) ;
//            $pat = "/" . '\$srv_g' . "/" ;
//            $qry = preg_replace( $pat, $srv_g, $qry ) ;
//            $pat = "/'#[^']*'/" ;
//            $qry = preg_replace( $pat, 'NULL' , $qry ) ;
            break ;
      }
      if ( strstr( $sct, 'c' ) != false ) {
         $sql = " SELECT count(*) as c FROM ( $qry ) q $Cnd   ; "  ;
         $rA['_qry'] .= " ## ( $sql ) ## " ;
         $rs1 = $data->getSelect( $sql, $rA, 'Dt', __LINE__ ) ;
         if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
         if (is_object($rs1))  if(!($rs1->EOF)) {
            $a = array() ;
            $a[0] = $rs1->f['c']  ;
            $a[1] = $pL ;
            $a[2] = $rs->f['ttl']  ;
            $a[4] = $rs->f['did'] ;
            $rA[0] = $a ;
         } ;
      } ;
      $qry = "SELECT " . $flt  . " FROM  ( $qry ) q $Cnd  $oBy $lmt "  ;
      $rs1 = $data->getSelect( $qry, $rA, 'Dt', __LINE__ ) ;
      if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
      if (is_object($rs1)) {
         $rA[1] = rsGetFlds ( $rs1) ;
         $rA[2] = rsGetData ( $rs1) ;
      }
      $rA['__Notice'] = $data->getNotice() ;
      $rA['_qry'] .= " ## ( $qry ) ## " ;
   }
//   echo "(" . json_encode($rA) . ")" ;
?>
