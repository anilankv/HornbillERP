<?php
   $p = empty( $_GET['p']) ? '1' : $_GET['p'] ;
   $v = empty( $_GET['v']) ? '0' : $_GET['v'] ;
   $f = empty( $_GET['f']) ? '0' : $_GET['f'] ;
   $c = empty( $_GET['c']) ? '0' : $_GET['c'] ;
   $a = empty( $_GET['a']) ? '0' : $_GET['a'] ;
   $pL = empty( $_GET['pL']) ? 10  : $_GET['pL'] ;
   $Cnd = empty( $_GET['Cnd']) ? '' : $_GET['Cnd'] ;
   $oBy = empty( $_GET['oBy']) ? '' : $_GET['oBy'] ;
   $ofs = empty( $_GET['ofs']) ? 1 : $_GET['ofs'] ;
   $flt = empty( $_GET['flt']) ? '*' : $_GET['flt'] ;
   $mod = empty( $_GET['mod']) ? 'd' : $_GET['mod'] ;
   $sct = empty( $_GET['sct']) ? 'c' : $_GET['sct'] ;
   $sql = empty( $_GET['sql']) ? '' : $_GET['sql'] ;
   $snm = empty( $_GET['sNm']) ? '' : $_GET['sNm'] ;
   $qry = " SELECT str, ttl, did, obf, obo FROM sw.grp_view WHERE vno = '$v' AND snm='$snm' ;" ;
   $p = ( $p < 1 ) ? 1 : $p ;
   $rs = $data->getSelect( $qry, $rA, 'O', __LINE__ ) ;
   $rA['_qry']  = $qry ;
   if (empty($rs->f['str'])) {
      echo "(" . json_encode($rA) . ")" ;
      return ;
   }
   if ($oBy == '' )$oBy = ($rs->f['obf'] == '' ) ? '' : " order by " . $rs->f['obf'] . " " . $rs->f['obo'] ;
   if ($Cnd != '' ) $Cnd =  ' WHERE ' . $Cnd  ;
   $lmt = (($sct == 'f')||($sct == 'cf')||($sct == 'c')) ? " LIMIT 0 " : " " ; 
   $lmt = (strstr( $sct, 'p' ) && ($pL != 'h') ) ?  " OFFSET ($p - 1) * $pL LIMIT $pL " : $lmt ; 
   $lmt = (strstr( $sct, 'm' ) && ($pL != 'h') ) ?  " OFFSET $ofs LIMIT $pL " : $lmt ; 
   switch ( $mod ) {
      case 'p' :
      case 'm' :
         $rA['_qry']  = "SELECT * FROM (" . $rs->f['str'] . " ) q " . $Cnd . $oBy ;
         echo "(" . json_encode($rA) . ")" ;
         return ;
         break ;
      case 'd' :
         $rA[1] = '' ;
         $rA[2] = '' ;
         $rA[3] = '' ;
         $dt  = fetch() ;
         $qry = getQryFrmPrimitive( $rs->f['str'], $dt )  ;
         break ;
   }
   if ( strstr( $sct, 'c' ) != false ) {
      $sql = " SELECT count(*) as c FROM ( $qry ) q $Cnd   ; "  ;
      $rA['_qry'] .= " ## ( $sql ) ## " ;
      $rs1 = $data->getSelect( $sql, $rA, 'O', __LINE__ ) ;
      if($err = $data->getError()) {
         $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
         echo "(" . json_encode($rA) . ")" ;
         exit ;
      }
      $a = array() ;
      $a[0] = $rs1->f['c']  ;
      $a[1] = $pL ;
      $a[2] = $rs->f['ttl']  ;
      $a[4] = $rs->f['did'] ;
      $rA[0] = $a ;
   } ;
   $qry = "SELECT " . $flt  . " FROM  ( $qry ) q $Cnd  $oBy $lmt "  ;
   $rs1 = $data->getSelect( $qry, $rA, 'O', __LINE__ ) ;
   if($err = $data->getError()) {
      $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
      echo "(" . json_encode($rA) . ")" ;
      exit ;
   }
   if (is_object($rs1)) {
      $rA[1] = rsGetFlds ( $rs1) ;
      $rA[2] = rsGetData ( $rs1) ;
   }
   $rA['__Notice'] = $data->getNotice() ;
   $rA['_qry'] .= " ## ( $qry ) ## " ;
?>
