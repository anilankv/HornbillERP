<?php
   $pL = empty( $_GET['pL']) ? 500  : $_GET['pL'] ;
   $wnm = empty( $_GET['wNm']) ? '*' : $_GET['wNm'] ;
   $ssrv = empty( $_GET['ssrv']) ? '' : $_GET['ssrv'] ;
   $pwF = empty( $_GET['pW']) ? true  : $_GET['pW'] ;
   $Cnd = empty($d_g['qP_g']) ? '' : getCnd( $d_g['qP_g']);
   $oBy = empty($d_g['qP_g']) ? '' : getOby( $d_g['qP_g']);
   $ofs = empty($d_g['qP_g']) ? '0' : getOfs( $d_g['qP_g']);
   $flt = empty($d_g['qP_g']) ? '*' : getFlt( $d_g['qP_g'], $sct_g);
   $c = empty($Cnd) ? '' : ' WHERE ' . preg_replace_callback('/\|/',function($m){ return '%' ; }, $Cnd ) ;
   $v = $d_g['_v'] ;
   $snm = empty( $_GET['sNm']) ? '' : $_GET['sNm'] ;
   $a = array() ;
   $fA = array() ;
   $rdt = array() ;
   $gs = array() ;
   $gf = array() ;
//print_r($d_g) ;
   $qry = " SELECT snm FROM sw.srv WHERE id = '$ssrv' ;" ;
   $rs = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
   $snm = (!($rs->EOF)) ? $rs->f['snm'] : 'NULL' ;
   $qry = " SELECT html FROM sw.grd_hdr WHERE snm = '$snm' AND wNm = '$wnm' ;" ;
   $rs = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
   $_H = '' ;
   if(!($rs->EOF)) {
      $_H = getQryFrmPrimitive( $rs->f['html'], $d_g )  ;
   }
   $qry = " SELECT str, ttl, did, obf, obo FROM sw.grp_view WHERE vno = '$v' AND snm='$snm' ;" ;
   $p = ( $p < 1 ) ? 1 : $p ;
   $rs = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
   if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
   $c = empty($Cnd) ? '' : ' WHERE ' . preg_replace_callback('/\|/',function($m){ return '%' ; }, $Cnd ) ;
   if (empty($rs->f['str'])) {
   } else {
      //if (empty($oBy))$oBy = ($rs->f['obf'] == '' ) ? '' : " order by " . $rs->f['obf'] . " " . $rs->f['obo'] ;
      //$lmt = (($sct_g == 'f')||($sct_g == 'cf')||($sct_g == 'c')) ? " LIMIT 0 " : " " ; 
      //$lmt = (strstr( $sct_g, 'p' ) && ($pL != 'h') ) ?  " OFFSET ($p - 1) * $pL LIMIT $pL " : $lmt ; 
      //$lmt = (strstr( $sct_g, 'm' ) && ($pL != 'h') ) ?  " OFFSET  $ofs LIMIT $pL " : $lmt ; 
      //$lmt = (($sct_g == 'f')||($sct_g == 'cf')||($sct_g == 'c')) ? " LIMIT 0 " : " " ; 
      //$lmt = (strstr( $sct_g, 'p' ) && ($pL != 'h') ) ?  " OFFSET ($p - 1) * $pL LIMIT $pL " : $lmt ; 
      if (empty($oBy))$oBy = ($rs->f['obf'] == '' ) ? '' : " order by " . $rs->f['obf'] . " " . $rs->f['obo'] ;
      $lmt = (($sct_g == 'f')||($sct_g == 'cf')||($sct_g == 'c')) ? " LIMIT 0 " : " " ; 
      //$lmt = (strstr( $sct_g, 'p' ) && ($pL_g != 'h') ) ?  " OFFSET ($p_g - 1) * $pL_g LIMIT $pL_g " : $lmt ; 
      //$lmt = (strstr( $sct_g, 'm' ) && ($pL_g != 'h') ) ?  " OFFSET  $ofs LIMIT $pL_g " : $lmt ; 
      $qry = getQryFrmPrimitive( $rs->f['str'], $d_g )  ;
      if ( strstr( $sct_g, 'c' ) != false ) {
         $sql = " SELECT count(*) as c FROM ( $qry ) q " . $c . "   ; "  ;
         $rs1 = $data->getSelect( $sql, $rA, 'S', __LINE__ ) ;
         if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
         if (is_object($rs1))  if(!($rs1->EOF)) {
            $a = array() ;
            $a[0] = $rs1->f['c']  ;
            $a[1] = $pL ;
            $a[2] = $rs->f['ttl']  ;
            $a[4] = $rs->f['did'] ;
         } ;
      } ;
      $sql = "select col, vf, typ, agf, ttl, wdt from sw.grd_dtl where snm = '$snm' and wnm= '$wnm' order by col; " ;
      $rs = $data->getSelect( $sql, $rA, 'S', __LINE__ ) ;
      while (! ($rs->EOF) ){
         $gs[$rs->f['col']] = $rs->f ; 
         $rs->MoveNext();
      }
      $rs0 = $data->getSelect( $qry . " limit 0 ", $rA, 'S', __LINE__) ;
      if (is_object($rs0)) {
         $qfA = rsGetFlds ( $rs0) ;
         foreach( $qfA as $i => $fldN ) {
            $gf[$fldN] = $gs[$i] ;
         } ; 
      }
      $qry = "SELECT " . $flt  . ", row_number() over ($oBy) _s FROM  ( $qry ) q $c  $oBy $lmt "  ;
      $rs1 = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
      if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
      if (is_object($rs1)) {
         $fA = rsGetFlds ( $rs1) ;
         $rdt = rsGetData ( $rs1) ;
      }
      $i = 0 ;
      $agA = array() ;
      $j = count($fA)-1 ;
      foreach ($rdt as $r) {
         $k = 0 ;
         foreach ($r as $c) {
            if($k >= $j ) continue ;
            if (($gf[$fA[$k]]['agf'] == 't') && ( ($gf[$fA[$k]]['typ'] == 'i') || ($gf[$fA[$k]]['typ'] == 'n') )) {
               $agA[$k] = empty($agA[$k]) ? $c : $agA[$k] + $c ;
            } else {
               $agA[$k] = '' ;
            }
            $k++ ;
         }
         $i++ ;
      }
      $rA['__Notice'] = $data->getNotice() ;
   }
   if ($f_g == 4) {
      $template->set_filenames(array( 'grdPdf' => 'grdPdf.tpl'));
      $template->assign_vars(array(
         "ghd"=> $_H
      )) ;
      $k = 0 ;
      $i = 0  ;
      foreach ($fA as $f) {
         if($k >= $j ) continue ;
         if( $gf[$fA[$k]]['vf'] == 'h' ) {
            $k++ ;
            continue ;
         }
         $wdt = ($gf[$fA[$k]]['vf'] == 'h' ) ? 0 : round($gf[$fA[$k]]['wdt'] * 0.8) ;
         $template->assign_block_vars("f",array(
            "ttl"  => $gf[$fA[$k]]['ttl'],
            "wdt"  => $wdt . 'px' ,
            "vf"  => ($gf[$fA[$k]]['vf'] == 'h' ) ? 'none' : 'block' ,
            "typ"  => $gf[$fA[$k]]['typ'],
            "agf"  => $gf[$fA[$k]]['agf']
         )) ;
         $k++ ;
      }
      foreach ($rdt as $r) {
         $template->assign_block_vars("r",array(
            "r"  => $i + 1 
         )) ;
         $k = 0 ;
         foreach ($r as $c) {
            if($k >= $j ) continue ;
            if( $gf[$fA[$k]]['vf'] == 'h' ) {
               $k++ ;
               continue ;
            }
            if($gf[$fA[$k]]['ttl'] == '#' ) {
               $c = $r[$j] ;
            }
            $template->assign_block_vars("r.c",array(
               "vf"  => ($gf[$fA[$k]]['vf'] == 'h' ) ? 'none' : 'block' ,
               "typ"  => $gf[$fA[$k]]['typ'],
               "c"  => $c
            )) ;
            $k++ ;
         }
         $i++ ;
      }
      $template->pparse('grdPdf');
   } else {
      $rA[0] = $a ;
      $rA[1] = $fA ;
      $rA[2] = $rdt ;
      $rA[3] = $agA ;
      $rA['_H'] = $_H ;
   }
?>
