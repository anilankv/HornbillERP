<?php
   if ( check_auth( $sNm_g, $sEv_g ) == false ){
      $rA['__Notice'] = 'Operation is not permitted !!' ;
      echo "(" . json_encode($rA) . ")" ;
      exit ;
   } ;
   $qry_all = '' ;
//   $rA['rQ'] = '';
   $rAIdx = 0 ;
   $rA['cexdt'] =  count( $exDt);
//   $rA['_qry'] = '';
   foreach ( $exDt as $i => $Dt ) {
      if ( !is_int($i) ) continue ;
      $evt = $Dt['__e'] ;
      $typ = (empty($Dt['__t'])) ? 'N' : $Dt['__t'] ;  
      $scr = $Dt['__s'] ;
      $wcnd = (empty($Dt['__w'])) ? 'wnm is null' : "wnm='{$Dt['__w']}'" ;  
      if ( $scr != $sNm_g ) continue ;
      $sql = "SELECT * from sw.exeq WHERE snm='$scr' and $wcnd and evt='$evt' and typ = '$typ' " ;
//      $rA['reQ'] = $sql ;
      $rs = $data->getSelect( $sql, $rA, 'X', __LINE__ ) ;
      if ( !($rs->EOF)) {
         $vno = $rs->f['ord'] ;
         $rV_g[$vno] =  empty($rV_g[$vno]) ? array() : $rV_g[$vno];
//         $rA['_pQ'] =  $rs->f['str'] ;
         $qry = getQryFrmPrimitive( $rs->f['str'], $Dt )  ;
         $rs = $data->getSelect( $qry, $rA, 'X', __LINE__ ) ;
         $qry_all .=  " q($i)< $qry ||" ;
         //if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
         $sid = ( $sid_g == -1 ) ? 'null' : "'$sid_g'" ;
         $lq = "INSERT INTO log.adl (sid, srv, tim, qry ) VALUES ( $sid, '$aS_g', now(), '" . preg_replace( "/'/", "''" , $qry )   . "' ) " ; 
         $data->getSelect( $lq, $rA, 'X', __LINE__ ) ;
         $rV_g[$vno][$evt] =  $rs->f ;
         if( isset($rs->f['id'] )){
            $rA[$rAIdx] = $rs->f['id'] ;
            $key_g =  (empty($key_g)||($key_g == 'NULL') ) ? $rs->f['id'] : $key_g ;
            $rAIdx++ ;
         }
      }
   }
   //if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
   $rA['__Notice'] = $data->getNotice() ;
//   $rA['_Aqry']     = $qry_all;
   if(!(isset( $rA[0]))) $rA[0] = '' ;
?>
