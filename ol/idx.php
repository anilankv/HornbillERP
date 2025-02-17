<?php
   $pl_g = 10 ; /* Page length in terms of row */ 
   $exDt  = fetch() ;
   $rs = $data->selectSet( "BEGIN ;" ) ;
   $ent_q = empty( $d_g['__ent'] ) ? $ent_g : $d_g['__ent'] ;
   $ent_q = empty( $ent_q ) ? 'NULL' : $ent_q ;
   if($st_g != 'RTN' ) {
      $q0 = "SELECT asv FROM admin.stg_clb WHERE etp=$ent_q and act='$aEv_g' and flg = 'B' ORDER BY ord " ;
      $rs0 = $data->getSelect ( $q0, $rA, 'I', __LINE__ );
      while ( !($rs0->EOF)) {
         $sdt_l = $data->getSrvVal($rs0->f['asv']) ;
         exe_club_act($sdt_l) ;
         $rs0->MoveNext();
      }
      exe_club_act($srvdt_g) ;
      $q0 = "SELECT asv FROM admin.stg_clb WHERE etp=$ent_q and act='$aEv_g' and flg = 'A' ORDER BY ord " ;
      $rs0 = $data->getSelect ( $q0, $rA, 'I', __LINE__ );
      while ( !($rs0->EOF)) {
         $sdt_l = $data->getSrvVal($rs0->f['asv']) ;
         exe_club_act($sdt_l) ;
         $rs0->MoveNext();
      }
   }
   $nt = 'NULL' ;
   $sl = 'NULL' ;
   $pn = 'NULL' ;
   if(!empty( $exDt['ext']) ) {
      if ($exDt['ext']['N']) $nt = "'{$exDt['ext']['N']}'" ; 
      if ($exDt['ext']['S']) $sl = "'{$exDt['ext']['S']}'" ; 
      if ($exDt['ext']['P']) $pn = "'{$exDt['ext']['P']}'" ; 
   }
   $q = "SELECT log.setEntityLog($ent_q, '$key_g', null, '$uid_g', '$gid_g','$aEv_g', $nt, $sl, $pn,'$st_g','$unt_g') " ;
   $rs = $data->getSelect ( $q, $rA, 'I', __LINE__ );
//   $rA['l_q'] = "( $q) "  ;
   if($ntc = $data->getNotice()) $rA['__Notice'] = (empty($rA['__Notice'])) ? $ntc : $rA['__Notice'] ;
   $qry = "SELECT log.delete_message( id ) from log.msg where srv='$aS_g' and val = '$key_g' " ;
   $rs = $data->getSelect ( $qry, $rA, 'I', __LINE__ );
//   $rA['delMsg_q'] = "( $qry ) "  ;
   if(($st_g != 'RTN' ) && ($st_g != 'HLD') ) {
      $qry = "SELECT nxt, cnd, fw1, tw1, fw2, tw2, fgp, tgp, unt, kvs, rmk, tut FROM admin.act_flow WHERE act= '$aS_g' order by ord " ;
      $rs1 = $data->getSelect ( $qry, $rA, 'I', __LINE__ );
//      $rA['actflw_q'] = "( $qry ) "  ;
      if($ntc = $data->getNotice()) $rA['__Notice'] = (empty($rA['__Notice'])) ? $ntc : $rA['__Notice'] ;
      $msgsetCnt = 0 ;
      while (! ($rs1->EOF) ){
         $fgp = $rs1->f['fgp'] ;
         if( ($fgp != $gid_g) && ($fgp != '-1') ) {
            $rs1->MoveNext();
            continue ;
         }
         $unt = $rs1->f['unt'] ;
//         $rA["actflw_$msgsetCnt"] = "fgp ($fgp) unt ($unt)" ;
         if( ($unt != $unt_g) && ($unt != '-1') ) {
            $rs1->MoveNext();
            continue ;
         }
         $tgp = getQryFrmPrimitive( $rs1->f['tgp'], $exDt[0] )  ;
         $tgp = ( $tgp == '' ) ? 'null' : $tgp ; 
         $cnd = (trim($rs1->f['cnd'])=='') ? '1=1' :  getQryFrmPrimitive( $rs1->f['cnd'], $exDt[0] ) ;
         $nxt = getQryFrmPrimitive( $rs1->f['nxt'], $exDt[0] )  ;
         $nxt = ( $nxt == '' ) ? 'null' : $nxt ; 
         $fw1 = getQryFrmPrimitive( $rs1->f['fw1'], $exDt[0] )  ;
         $fw2 = getQryFrmPrimitive( $rs1->f['fw2'], $exDt[0] )  ;
         $kvs = getQryFrmPrimitive( $rs1->f['kvs'], $exDt[0] )  ;
         $tw1 = getQryFrmPrimitive( $rs1->f['tw1'], $exDt[0] )  ;
         $tw2 = getQryFrmPrimitive( $rs1->f['tw2'], $exDt[0] )  ;
         $rmk = getQryFrmPrimitive( $rs1->f['rmk'], $exDt[0] )  ;
         $tut = getQryFrmPrimitive( $rs1->f['tut'], $exDt[0] )  ;
         $tut = ( $tut == '' ) ? 'null' : $tut ; 
         $qry = "SELECT 1 WHERE $cnd " ;
 //        $rA["actflw_$msgsetCnt"] .= "fgp ($fgp) unt ($unt) qry ($qry) "  ;
         $rs2= $data->getSelect ( $qry, $rA, 'I', __LINE__ );
         if (! ($rs2->EOF) ){
            if(!empty($rA[0])) $key_g = $rA[0] ;
            $key = (empty($kvs)) ? "'$key_g'" : "'$kvs'" ;
            $qry = "SELECT log.create_message ('$sid_g'::int,$nxt::int,$key::varchar , '$fw1'::varchar, '$fw2'::varchar, '$tw1'::varchar, '$tw2'::varchar, $tgp::int, '$rmk'::varchar, $tut::int, $aS_g ) ; " ;
//            $rA["mQry$msgsetCnt"] ='##' . $qry . " |kvs|$kvs|fgp|$fgp|unt|$unt|tgp|$tgp|cnd|$cnd|fw1|$fw1|fw2|$fw2|tw1|$tw1|tw2|$tw2||##" ;
            $rs = $data->getSelect ( $qry, $rA, 'I', __LINE__ );
         }
         $msgsetCnt++ ;
         $rs1->MoveNext();
      }
      $qry = "SELECT log.switchEntityStage('$sid_g', '$aS_g', '$key_g' ) ; " ;
      $rs = $data->getSelect ( $qry, $rA, 'I', __LINE__ );
//      $rA['swtEntQ' . __LINE__] = $qry ;
   } else if ($st_g == 'RTN') {
      $qry = "SELECT log.returnEntity('$sid_g', '$aS_g', '$key_g', 'Returned entity ' ) ; " ;
      $rs = $data->getSelect ( $qry, $rA, 'I', __LINE__ );
//      $rA['retEntQ' . __LINE__] = $qry ;
      $qry = "SELECT log.switchReturnEntityStage('$sid_g', '$aS_g', '$key_g' ) ; " ;
      $rs = $data->getSelect ( $qry, $rA, 'I', __LINE__ );
//      $rA['swtEntQ' . __LINE__] = $qry ;
   }
   if($ntc = $data->getNotice()) $rA['__Notice'] = (empty($rA['__Notice'])) ? $ntc : $rA['__Notice'] ;
   $data->selectSet( "COMMIT ;" ) ;
   if (count($rA)) echo "(" . json_encode($rA) . ")" ;
   function exe_club_act($sdt_p) {
      //global $exDt, $data, $rA, $sid_g, $aS_g, $script_path, $unt_g, $unt_u, $gid_g, $f_g, $d_g, $ent_q, $st_g ;
      foreach ($GLOBALS as $gn => $v) {
         global $$gn;
      }
      if ( $sdt_p['scf'] != 'f' ) {
         if(!empty($sdt_p['scp']))include $script_path . $sdt_p['scp'] . '.php' ; 
      } else {
         $template->set_filenames(array( $sdt_p['bdy'] => $sdt_p['bdy'] . '.tpl'));
         $template->pparse($sdt_p['bdy']);
      }
   } ;
?>
