<?php
   $Cnd = empty($d_g['qP_g']) ? '' : getCnd( $d_g['qP_g']);
   $oBy = empty($d_g['qP_g']) ? '' : getOby( $d_g['qP_g']);
   $ofs = empty($d_g['qP_g']) ? '0' : getOfs( $d_g['qP_g']);
   $flt = empty($d_g['qP_g']) ? '*' : getFlt( $d_g['qP_g'], $sct_g);
   $c = empty($Cnd) ? '' : ' WHERE ' . preg_replace_callback('/\|/',function($m){ return '%' ; }, $Cnd ) ;
   if(is_numeric($v_g)) $q0 = " SELECT str, ttl, did, obf, obo FROM sw.grp_view WHERE vno = '$v_g' AND snm='$sNm_g' " ;
   else $q0 = "select sw.getTblQry('$v_g') as str" ;
   $p_g = ( $p_g < 1 ) ? 1 : $p_g ;
   $rs = $data->getSelect( $q0, $rA, 'P', __LINE__ ) ;

   if (empty($rs->f['str'])) {
//      echo "(" . json_encode($rA) . ")" ;
//      return ;
   } else {
      $qry = getQryFrmPrimitive( $rs->f['str'], $d_g )  ;
      if (empty($oBy))$oBy = ($rs->f['obf'] == '' ) ? '' : " order by " . $rs->f['obf'] . " " . $rs->f['obo'] ;
      $lmt = (($sct_g == 'f')||($sct_g == 'cf')||($sct_g == 'c')) ? " LIMIT 0 " : " " ; 
      $lmt = (strstr( $sct_g, 'p' ) && ($pL_g != 'h') ) ?  " OFFSET ($p_g - 1) * $pL_g LIMIT $pL_g " : $lmt ; 
      $lmt = (strstr( $sct_g, 'm' ) && ($pL_g != 'h') ) ?  " OFFSET  $ofs LIMIT $pL_g " : $lmt ; 
      switch ( $dmd_g ) {
//         case 'p' :
//         case 'm' :
//            $rA['_qry']  = "SELECT *, row_number() over ($oBy) _s FROM (" . $rs->f['str'] . " ) q " . $Cnd . $oBy ;
//            echo "(" . json_encode($rA) . ")" ;
//            return ;
//            break ;
         case 'd' :
            $rA[1] = '' ;
            $rA[2] = '' ;
            $rA[3] = '' ;
//            $qry = getQryFrmPrimitive( $rs->f['str'], $d_g )  ;
            break ;
      }
      if ( strstr( $sct_g, 'c' ) != false ) {
//         $qry = getQryFrmPrimitive( $rs->f['str'], $d_g )  ;
         $sql = " SELECT count(*) as c FROM ( $qry ) q " . $c . "   ; "  ;
         $rs1 = $data->getSelect( $sql, $rA, 'S', __LINE__ ) ;
         if (is_object($rs1))  if(!($rs1->EOF)) {
            $a = array() ;
            $a[0] = $rs1->f['c']  ;
            $a[1] = $pL_g ;
            $a[2] = $rs->f['ttl']  ;
            $a[4] = $rs->f['did'] ;
            $rA[0] = $a ;
         } ;
      } ;
      $qry = "SELECT " . $flt  . ", row_number() over ($oBy) _s FROM  ( $qry ) q $c  $oBy $lmt "  ;
      $rs1 = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
      if (is_object($rs1)) {
         $rA[1] = rsGetFlds ( $rs1) ;
         $rA[2] = rsGetData ( $rs1) ;
      }
      $rA['__Notice'] = $data->getNotice() ;
   }
?>

