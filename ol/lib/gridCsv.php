<?php
   $sct = empty( $_GET['sct']) ? 'd' : $_GET['sct'] ;
   $pL = empty( $_GET['pL']) ? 500  : $_GET['pL'] ;
   $Cnd = empty( $_GET['Cnd']) ? ''  : $_GET['Cnd'] ;
   $oBy = empty( $_GET['oBy']) ? '' : $_GET['oBy'] ;
   $flt = empty( $_GET['flt']) ? '*' : $_GET['flt'] ;
   $afl = empty( $_GET['_aStr']) ? '*' : $_GET['_aStr'] ;
   $wNm = empty( $_GET['wNm']) ? '*' : $_GET['wNm'] ;
   $ssrv = empty( $_GET['ssrv']) ? '' : $_GET['ssrv'] ;
   $pwF = empty( $_GET['pW']) ? true  : $_GET['pW'] ;
   //$afl = empty( $_GET['_aStr']) ? '*' : $_GET['_aStr'] ;
   //$Cnd = empty( $d_g['_sCnd']) ? $Cnd  : $d_g['_sCnd'] ;
   //$oBy = empty( $d_g['_oStr']) ? $oBy : $d_g['_oStr'] ;
   //$flt = empty( $d_g['_fStr']) ? $flt : $d_g['_fStr'] ;
   //$afl = empty( $d_g['_aStr']) ? $afl : $d_g['_aStr'] ;
   $v = $d_g['_v'] ;
   $Cnd = getCnd( $d_g['qP_g']);
   $oBy = getOby( $d_g['qP_g']);
   $ofs = getOfs( $d_g['qP_g']);
   $flt = getFlt( $d_g['qP_g'], $sct);
   $sNm = empty( $_GET['sNm']) ? '' : $_GET['sNm'] ;
   $a = array() ;
   $fA = array() ;
   $rdt = array() ;
   $gs = array() ;
   $gf = array() ;
//print_r($d_g) ;
   $qry = " SELECT snm FROM sw.srv WHERE id = '$ssrv' ;" ;
   $rs = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
   $sNm = (!($rs->EOF)) ? $rs->f['snm'] : 'NULL' ;
   $qry = " SELECT html FROM sw.grd_hdr WHERE snm = '$sNm' AND wNm = '$wNm' ;" ;
   $rs = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
   $_H = '' ;
   if(!($rs->EOF)) {
      $_H = getQryFrmPrimitive( $rs->f['html'], $d_g )  ;
   }
   $qry = " SELECT str, ttl, did, obf, obo FROM sw.grp_view WHERE vno = '$v' AND snm='$sNm' ;" ;
   $p = ( $p < 1 ) ? 1 : $p ;
   $rs = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
   if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
   $c = empty($Cnd) ? '' : ' WHERE ' . preg_replace_callback('/\|/',function($m){ return '%' ; }, $Cnd ) ;
   if (empty($rs->f['str'])) {
   } else {
      if (empty($oBy))$oBy = ($rs->f['obf'] == '' ) ? '' : " order by " . $rs->f['obf'] . " " . $rs->f['obo'] ;
      $lmt = (($sct == 'f')||($sct == 'cf')||($sct == 'c')) ? " LIMIT 0 " : " " ; 
      $lmt = (strstr( $sct, 'p' ) && ($pL != 'h') ) ?  " OFFSET ($p - 1) * $pL LIMIT $pL " : $lmt ; 
      $lmt = (strstr( $sct, 'm' ) && ($pL != 'h') ) ?  " OFFSET  $ofs LIMIT $pL " : $lmt ; 
      $lmt = (($sct == 'f')||($sct == 'cf')||($sct == 'c')) ? " LIMIT 0 " : " " ; 
      $lmt = (strstr( $sct, 'p' ) && ($pL != 'h') ) ?  " OFFSET ($p - 1) * $pL LIMIT $pL " : $lmt ; 
      $qry = getQryFrmPrimitive( $rs->f['str'], $d_g )  ;
      if ( strstr( $sct, 'c' ) != false ) {
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
      $sql = "select col, vf, typ, agf, ttl, wdt from sw.grd_dtl where snm = '$sNm' and wnm= '$wNm' order by col; " ;
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
      $j = count($fA)-1 ;
      $agA = array() ;
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
      $str = '' ;
      $k = 0 ;
      $i = 0  ;
      foreach ($fA as $f) {
         if($k >= $j ) continue ;
         if( $gf[$fA[$k]]['vf'] == 'h' ) {
            $k++ ;
            continue ;
         }
         $str .= $gf[$fA[$k]]['ttl'] . "\t" ;
         $k++ ;
      }
      foreach ($rdt as $r) {
         $k = 0 ;
         $str .= "\n" ;
         foreach ($r as $c) {
            if($k >= $j ) continue ;
            if( $gf[$fA[$k]]['vf'] == 'h' ) {
               $k++ ;
               continue ;
            }
            if($gf[$fA[$k]]['ttl'] == '#' ) $c = $r[$j] ;
            $str .= $c . "\t" ;
            $k++ ;
         }
         $i++ ;
      }
   }
   $str .= "\n" ;
   if (!empty($str)) {
      $sts = 0 ;
      $cpth = $conf['upload_path'] ;
      $sdir = "" ;
      $sql =  "SELECT p.op FROM sw.widprm p, sw.srv s WHERE s.id=$ssrv AND p.wnm='$wNm' AND p.snm=s.snm " ;
      $rs = $data->getSelect( $sql, $rA, 'S', __LINE__ ) ;
      if($err = $data->getError()) $rA['__Error'] .=  ' |1| ' . $err  ; 
      $aDt = getdate () ;
      $dts = $aDt['year'] . str_pad($aDt['mon'], 2, '0', STR_PAD_LEFT) . str_pad($aDt['mday'], 2, '0', STR_PAD_LEFT) ;
      if (! ($rs->EOF) ){
         $opA = preg_split('/\|/', $rs->f['op']);
         $sdir = (empty($opA[0])) ? "/$dts/" : $opA[0] . "/$dts/" ; 
      }
      $sdir = (empty($sdir)) ? $dts : $sdir ;
      $tS = round(microtime(true)*1000) ; 
      if (!is_dir($cpth . $sdir)) mkdir($cpth . $sdir, 0700, true);
      //if ( $mod == 'CSV' ) {
         $fN = 'grid.csv' ;
         file_put_contents( "$cpth$sdir" . '/' . "$fN.$tS" , $str);
         $sts = 1 ;
      //}
      $fno_g = '-1' ;
      if ( $sts == 1 ) {
         if (!empty($opA[1])) {
            include_once($root_path .'/scripts/' . $opA[1] . ".php" );
         }
         $sql="INSERT INTO log.fileupd (ssn,cpth,pth,fnm,fts,tm,srv,rmk, unt) VALUES ( $sid_g,'$cpth','$sdir','$fN','$tS',now(),$ssrv,'CSV creation', '$unt_g' ) returning id ;" ;
         $rs = $data->getSelect( $sql, $rA, 'S', __LINE__ ) ;
         if( is_object($rs)) $fno_g = $rs->f['id'] ;
      }
      $rA['fN'] = $fno_g ;
      $sql = "SELECT * from sw.exeq WHERE snm='$sNm' and ord='$v' and typ = 'N' " ;
      $rs = $data->getSelect( $sql, $rA, 'S', __LINE__ ) ;
      if ( !($rs->EOF)) {
         $vno = $rs->f['ord'] ;
         $rV_g[$vno] =  empty($rV_g[$vno]) ? array() : $rV_g[$vno];
         $pat = "/" . '\$fno_g' . "/" ;
         $qry = preg_replace( $pat, $fno_g,$rs->f['str']  ) ;
         $exDt  = fetch() ;
         $qry = getQryFrmPrimitive( $qry, $exDt)  ;
         $rs = $data->getSelect( $qry, $rA, 'S', __LINE__ ) ;
         $sid = ( $sid_g == -1 ) ? 'null' : "'$sid_g'" ;
         $lq = "INSERT INTO log.adl (sid, srv, tim, qry ) VALUES ( $sid, '$aS_g', now(), '" . preg_replace( "/'/", "''" , $qry )   . "' ) " ; 
         $data->getSelect( $lq, $rA, 'S', __LINE__ ) ;
         if ( !is_object($rs)) {
            $rs = $data->selectSet( "ROLLBACK ;") ;
            $rA['__Notice'] = $data->getNotice() ;
            echo "(" . json_encode($rA) . ")" ;
            exit ;
         } else if (!($rs->EOF)) {
            $rV_g[$vno][$evt] =  $rs->f ;
         }
         if( isset($rs->f['id'] )){
            $rA[$rAIdx] = $rs->f['id'] ;
            $key_g =  (($key_g == '')||($key_g == 'NULL') ) ? $rs->f['id'] : $key_g ;
            $rAIdx++ ;
         }
      }
   } else {
      $rA['__Error'] =  'Blank Document Ignored ' ; 
   }
?>
