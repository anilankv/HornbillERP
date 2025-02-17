<?php
   $fdt=isset($_GET['fdt']) ? $_GET['fdt']:'';
   $tdt=isset($_GET['tdt']) ? $_GET['tdt']:'';
   $rid=isset($_GET['rid']) ? $_GET['rid']:'';
   $coa=isset($_GET['coa']) ? $_GET['coa']:'';
   $acn=isset($_GET['acn']) ? $_GET['acn']:'null';
   $tb = "&nbsp;&nbsp;&nbsp;" ;

      if ($rid == 'NULL') exit("<font color=red><h2>Choose a Report Type</h2></font>");
      $rs = $data->selectSet("SELECT * FROM acc.repconf WHERE id = '$rid' " ) ;
      if ($rs->EOF) {
         echo "<P>No such reports configured !!!</P>" ;
      }
      $sA = array( ) ;
      $tF = 1 ; 
      $rTp = $rs->f['typ'] ;
      $c_t = 'acc.coa c' ;
      $gd_t = 'acc.coagrp_dtl gd' ;
      $rd_t = 'acc.repconf_dtl rd' ;
      $g_t = 'acc.coagrp g' ;
      $cc_t = 'acc.coa_cat cc' ;
      $b_t = 'acc.coa_dob b' ;
//echo "<P> Unit $unt_g </P>" ;
      $template->set_filenames(array('acc_rep'=>"acc_rep$rTp.tpl"));
      $org=$data->selectSet("Select nam,adr1,adr2,adr3 from org.units where id=$unt_g");
if (($rTp == 'L')) {
   if ($coa=='NULL') $coa='CNULL';
   $rsa=$data->selectSet("Select nam,cod from acc.coa where id::varchar=substr('$coa',2)");
}
      $template->assign_vars(array(
         "lhd"=>$rs->f['lhd'],
         "rhd"=>$rs->f['rhd'],
         "fdt"=>$fdt,"tdt"=>$tdt,
         "nam"=>$org->f['nam'],
         "adr1"=>$org->f['adr1'],
         "adr2"=>$org->f['adr2'],
         "adr3"=>$org->f['adr3'],
         "coa"=>$rsa->f['nam'],
         "ccod"=>$rsa->f['cod'],
         "rnam"=>$rs->f['nam']
      ));
      if (($rTp == 'L') || ($rTp == 'G')) {
         $sA[0] = array( 's' => 'L' ) ;
	 $cTp = substr($coa,0,1) ;


         if ($acn!='NULL')  { $aTp = substr($acn,0,1) ; $acn = substr($acn,1) ;}
         $cId = substr($coa,1) ;
         $ob = getOpenBal($cId, $fdt, $unt_g, $cTp, $acn);
         $cb = $ob ;
	 //echo "<P> opening bal ( $ob )  is   $cId with  fdt($fdt) unt($unt_g) cTp ($cTp)  acn  ($acn) </p>" ;
         $q=" SELECT distinct  id,tdt::date,adt,dhd,chd,dac,cac,cnm,nrt,typ,rid,cmf,goc,gmf,dbt,cdt,ttp,fld1,fld2,fld3 from acc.get_ldgr('$fdt', '$tdt', $cId,'$acn'::char(16), $unt_g,'$rTp',$rid)" ;
   echo $q;
         $r=$data->selectSet($q) ;
         while (! ($r->EOF) ){
            $camt = $r->f['cdt'] ;
            $damt = $r->f['dbt'] ;
            $cb += $r->f['cmf'] * ( $camt + $damt) * $r->f['gmf'];
//echo "<P> cb = $cb {$r->f['cmf']} * ( $camt - $damt) * {$r->f['gmf']} </P>" 
            if( $r->f['ttp'] == 'D' ) {
               $camt = '&nbsp;' ;
            } else if( $r->f['ttp'] == 'C' ) {
               //$damt = '----' ;
               $damt = '&nbsp;' ;
            }
            $template->assign_block_vars("stmt",array(
               "id"  => $r->f['id'],
               "adt"  => $r->f['adt'],
               "nrtn"  => $r->f['cnm'] . '-' . $r->f['nrt'],
               "tref"  => $r->f['typ'],
               "rid"  => $r->f['rid'],
               "debit"  => $damt,
               "credit"  => $camt
            )) ;
            $r->MoveNext();
         }
         $template->assign_vars(array(
            "ob"  => number_format($ob,2),
            "cb"  => number_format($cb,2),
            "fdt"  => $fdt,
            "tdt"  => $tdt
         ));
         $template->pparse('acc_rep');
         exit;
         return ;
      }
//--------------------- RECEIPTS AND PAYMENTS-------------//
         if ($rTp == 'R'){
      $q = "SELECT c,nm,amt,sd,tp from acc.rp_stat('$fdt', '$tdt', $rid,'$unt_g','R') ;" ;
      //echo $q;
      $r = $data->selectSet($q) ;
      $q1 = getOpenBal(1,$fdt, $unt_g, 'C', 'NULL');
      $q2 = getOpenBal(5,$fdt, $unt_g, 'G', 'NULL');
      $q3 = "SELECT 'CASH IN HAND' as nm,$q1 as amt;" ;
      $r3 = $data->selectSet($q3) ;
      $q4 = "SELECT 'CASH IN BANK,DOCUMENTS' as nm,$q2 as amt;" ;
      $r4 = $data->selectSet($q4) ;
      $sum1=0;
      $sA = array() ;
      $sA['L'] = array() ;
      $sA['R'] = array() ;
      $l = 3 ;
      $p = 1 ;
      $sA['L'][1] = $r3->f ;
      $sA['L'][2] = $r4->f ;
      while (! ($r->EOF) ){
         if ($r->f['tp'] =='1' ) {
            $sA['L'][$l] =  $r->f ;
            $l++ ;
         } else {
            $sA['R'][$p] =  $r->f ;
            $p++ ;
         } 
         $r->MoveNext();
      }
      $q5 = getCloseBal(1,$tdt, $unt_g, 'C', 'NULL');
      $q6 = getCloseBal(5,$tdt, $unt_g, 'G', 'NULL');
      $q7 = "SELECT 'CASH IN HAND' as nm,$q5 as amt;" ;
      $r7 = $data->selectSet($q7) ;
      $q8 = "SELECT 'CASH IN BANK,DOCUMENTS' as nm,$q6 as amt;" ;
      $r8 = $data->selectSet($q8) ;
      $sA['R'][$p++] =  $r7->f ;
      $sA['R'][$p] =  $r8->f ;
      $sum2=0;
      foreach ($sA['L'] as $i => $g) {
         $sum1=$sum1+$g['amt'];
          $template->assign_block_vars("l",array(
             "unm"   => $g['nm'],
             "amt"   => $g['amt']
          ));
      }
      foreach ($sA['R'] as $i => $g) {
         $sum2=$sum2+$g['amt'];
          $template->assign_block_vars("r",array(
               "unm"   => $g['nm'],
               "amt"   => $g['amt']
            ));
      }
         $template->assign_vars(array(
            "lft"  => $sum1,
            "rht"  => $sum2
         ));
      
      $template->pparse('acc_rep');
         exit;
         return ;
    }
//-----------------------END RECEIPTS AND PAYMENTS -----------------------//
//-----------------------START TRIAL BALANCE -----------------------//
    if ($rTp == 'T'){
       $i = 0 ;
      $q = "select c.cod,c.unm as nm, acc.get_openbal('$fdt'::date, c.id, '$unt_g', 'C' ,null) as ob, ( select COALESCE(sum(t.amt),0) from acc.trans t WHERE  t.chd = c.id and  t.adt BETWEEN '$fdt'::date AND '$tdt'::date AND t.unt='$unt_g' ) as cb , ( select COALESCE(sum(t.amt),0) from acc.trans t WHERE  t.dhd = c.id and  t.adt BETWEEN '$fdt'::date AND '$tdt'::date AND t.unt='$unt_g' ) as db, acc.get_closebal('$tdt'::date, c.id,'$unt_g', 'C' ,null) as xb from acc.coa c ;" ;
      //echo $q;
      $cBl = 0.0 ;
      $dBl = 0.0 ;
      $r = $data->selectSet($q) ;
      while (! ($r->EOF) ){
         $cBl += $r->f['cb'] ;
         $dBl += $r->f['db'] ;
         $template->assign_block_vars("rw",array(
            "nm"  => $r->f['cod']."-".$r->f['nm'] ,
            "ob"  =>  number_format($r->f['ob'],2),
            "xb"  =>  number_format($r->f['xb'],2),
            "cb"  =>  number_format($r->f['cb'],2),
            "db"  =>  number_format($r->f['db'],2)
         )) ;
         $r->MoveNext();
      }
      $template->assign_vars(array(
           "ctl"    => number_format( $cBl,2),
           "dtl"    => number_format( $dBl,2),
      ));
      $template->pparse('acc_rep');
      exit;
    }
//-----------------------END TRIAL BALANCE -----------------------//
      $g_we  = $cg_we = 't' ; 
      $g_sf  = $cg_sf = 1 ; 
      $i = 0 ;
      $q = "SELECT distinct g, cg, c, nm, od, ob, xb, db, cb, we, zf, mf, gf, tp, sd from acc.get_stat('$fdt', '$tdt', $rid,'$unt_g','R') order by sd,od ;" ;
      //echo $q;
      $r = $data->selectSet($q) ;
      while (! ($r->EOF) ){
         $sd = $r->f['sd'] ;
         $tp = $r->f['tp'] ;
         if(empty( $sA[$sd] )) {
            $sA[$sd] = array() ;
            $sA[$sd]['dt'] = array() ;
            $sA[$sd]['ob'] = 0 ;
            $sA[$sd]['xb'] = 0 ;
            $sA[$sd]['bl'] = 0 ;
            $sA[$sd]['cb'] = 0 ;
            $sA[$sd]['db'] = 0 ;
         }
         $sA[$sd]['dt'][$i] = $r->f ;
         $sA[$sd]['dt'][$i]['bl'] = $r->f['xb'] - $r->f['ob'] ;
         $sf = 1 ;
         if ($rTp == 'B') $zf = (($r->f['zf']=='f')&& ($r->f['xb'] == 0 )) ? 0 : 1;
         else if ($rTp == 'C') $zf = (($r->f['zf']=='f')&&($r->f['ob'] == 0 )&&($r->f['xb'] == 0 )&&($r->f['cb'] == 0 )&&($r->f['db'] == 0 )) ? 0 : 1;
         else if ($rTp == 'T') $zf = (($r->f['zf']=='f')&&($r->f['ob'] == 0 )&&($r->f['xb'] == 0 )&&($r->f['cb'] == 0 )&&($r->f['db'] == 0 )) ? 0 : 1;
         else if ($rTp == 'S') $zf = (($r->f['zf']=='f')&&($r->f['ob'] == 0 )&&($r->f['xb'] == 0 )&&($r->f['cb'] == 0 )&&($r->f['db'] == 0 )) ? 0 : 1;
         if($tp == 'G') {
            $g_we = $r->f['we'] ;
            $g_sf = $sf = (($r->f['zf'] == 'f') && ($zf == 0 )) ? 0 : 1 ;
            $sA[$sd]['ob'] += $r->f['ob'] ;
            $sA[$sd]['xb'] += $r->f['xb'] ;
            //$sA[$sd]['bl'] += $r->f['bl'] ;
            if($sd=='L') $sA[$sd]['bl'] += $r->f['cb'] ;
            else if($sd=='R') $sA[$sd]['bl'] += $r->f['db'] ;
            $sA[$sd]['cb'] += $r->f['cb'] ;
            $sA[$sd]['db'] += $r->f['db'] ;
            $sA[$sd]['dt'][$i]['id'] = $r->f['g'] ;
         } else if($tp == 'CG') {
            $cg_we = $r->f['we'] ;
            $cg_sf = $sf = (($g_sf == '0')||($g_we == 'f')||($zf == 0)) ? 0 : 1 ;
            $sA[$sd]['dt'][$i]['id'] = $r->f['cg'] ;
         } else if($tp == 'CC') {
            $sf = (($cg_sf == '0')||($cg_we == 'f')||($zf == 0)) ? 0 : 1 ;
            $sA[$sd]['dt'][$i]['id'] = $r->f['c'] ;
         } else {
            $sf = $sf = (($g_sf == '0')||($g_we == 'f')||($zf == 0)) ? 0 : 1 ;
            $sA[$sd]['dt'][$i]['id'] = $r->f['c'] ;
            $sA[$sd]['cb'] += $r->f['cb'] ;
            $sA[$sd]['db'] += $r->f['db'] ;
         }
         $sA[$sd]['dt'][$i]['sf'] = $sf ;
/*
echo "<p></P>" ;
if(( $r->f['db'] != 0) && ( $r->f['cb'] != 0) )   echo "<p> $i ";
 if( $r->f['db'] != 0)  echo " $sd Debit Balance (" . $r->f['db'] . ") " . $sA[$sd]['db'] ;
 if( $r->f['cb'] != 0)  echo " $sd Credit Balance (" . $r->f['cb'] . ") " . $sA[$sd]['cb'] ;
if(( $r->f['db'] != 0) && ( $r->f['cb'] != 0) )   echo "</p>";
*/
         $i++ ;
         $r->MoveNext();
      }
      $creditbal = $sA[$sd]['cb'];
      $debitbal = $sA[$sd]['db'];
//   echo "<p> Credit Balance ".$creditbal. "    Debit Balance ".$debitbal."</p>";
      if ($rTp == 'C') {
         $c_ob = getOpenBal(1,$fdt, $unt_g, 'C', $acn);
         $c_cb = getCloseBal(1,$fdt, $unt_g, 'C',$acn);
         $b_ob = getOpenBal(2,$fdt, $unt_g, 'C' , $acn);
         $b_cb = getCloseBal(2,$fdt, $unt_g, 'C',$acn);
         $sA['L']['bl'] = $sA['L']['bl'] + $c_ob + $b_ob ;
         $sA['R']['bl'] = $sA['R']['bl'] + $c_cb + $b_cb ;
      }
      if ( !empty($sA['R'])) {
         if ( $sA['L']['xb'] > $sA['R']['xb'] ) {
         $sA['R']['dxb'] = $sA['L']['xb'] - $sA['R']['xb'] ;
         $sA['R']['ldxb'] =  "Difference " ;
         $xb =  $sA['L']['xb'] ;
         } else if ( $sA['R']['xb'] > $sA['L']['xb'] ) {
         $sA['L']['dxb'] = $sA['R']['xb'] - $sA['L']['xb'] ;
         $sA['L']['ldxb'] =  "Difference " ;
         $xb =  $sA['R']['xb'] ;
         }
         if ( $sA['L']['bl'] > $sA['R']['bl'] ) {
         $sA['R']['dbl'] = $sA['L']['bl'] - $sA['R']['bl'] ;
         $sA['R']['ldbl'] =  "Difference " ;
         $bl =  $sA['L']['bl'] ;
         } else if ( $sA['R']['bl'] > $sA['L']['bl'] ) {
         $sA['L']['dbl'] = $sA['R']['bl'] - $sA['L']['bl'] ;
         $sA['L']['ldbl'] =  "Difference " ;
         $bl =  $sA['R']['bl'] ;
         }
         $sA['R']['tl'] = $rs->f['rhd'] ;
      }
      $sA['L']['tl'] = $rs->f['lhd'] ;
      foreach ($sA as $sd => $S) {
         $template->assign_block_vars("sd",array( 
               "sd"   => $sd,
               "tl"   => $S['tl'],
               "ob"   => number_format($S['ob'],2),
               "xb"   => number_format($S['xb'],2),
               "cb"   => number_format($S['cb'],2),
               "bl"   => number_format($S['bl'],2),
               "db"   => number_format($S['db'],2),
               "dbl"  => empty($S['dbl'])?'&nbsp;' :number_format($S['dbl'],2),
               "dxb"  => empty($S['dxb'])?'&nbsp;' : number_format($S['dxb'],2),
               "ldxb" => empty($S['ldxb']) ? '&nbsp;' : $S['ldxb'],
               "ldbl" => empty($S['ldbl']) ? '&nbsp;' : $S['ldbl']
         ));
         foreach ($S['dt'] as $i => $g) {
            if ($g['sf'] == 1) {
               $template->assign_block_vars("sd.rw",array(
                     "nm"  => $g['nm'] ,
                     "cob" => ($g['tp']=='G') ? '&nbsp;' : number_format($g['ob'],2),
                     "cxb" => ($g['tp']=='G') ? '&nbsp;' : number_format($g['xb'],2),
                     "ccb" => ($g['tp']=='G') ? '&nbsp;' : number_format($g['cb'],2),
                     "cbl" => ($g['tp']=='G') ? '&nbsp;' : number_format($g['bl'],2),
                     "cdb" => ($g['tp']=='G') ? '&nbsp;' : number_format($g['db'],2),
                     "gob" => ($g['tp']=='G') ? number_format($g['ob'],2) : '&nbsp;',
                     "gxb" => ($g['tp']=='G') ? number_format($g['xb'],2) : '&nbsp;',
                     "gcb" => ($g['tp']=='G') ? number_format($g['cb'],2) : '&nbsp;',
                     "gbl" => ($g['tp']=='G') ? number_format($g['bl'],2) : '&nbsp;',
                     "gdb" => ($g['tp']=='G') ? number_format($g['db'],2) : '&nbsp;',
                     "ob"  =>  number_format($g['ob'],2),
                     "xb"  =>  number_format($g['xb'],2),
                     "cb"  =>  number_format($g['cb'],2),
                     "bl"  =>  number_format($g['bl'],2),
                     "db"  =>  number_format($g['db'],2),
                     "id"  => $g['id'],
                     "gf"  => ($g['gf'] == '1') ? 'C' : 'D',
                     "mf"  => ($g['mf'] == '1') ? 'C' : 'D',
                     "tp"  => $g['tp']
               )) ;
            }
         }
      }
      $template->assign_vars(array(
           "xb"    => number_format($xb,2),
      //     "bl"    => number_format($bl,2),
           "bl"    => number_format( $sA['L']['bl'],2),
           "rbl"    => number_format( $sA['L']['bl'],2),
           "pbl"    => number_format( $sA['R']['bl'],2),
           "ctl"    => number_format( $creditbal,2),
           "dtl"    => number_format( $debitbal,2),
           "c_ob"  => number_format($c_ob,2),
           "c_cb"  => number_format($c_cb,2),
           "b_ob"  => number_format($b_ob,2),
           "b_cb"  => number_format($b_cb,2),
           "t_ob"  => number_format($c_ob + $b_ob,2),
           "t_cb"  => number_format($c_cb + $b_cb,2)
      ));
      $template->pparse('acc_rep');
      exit;
      
   function getFncStr($f, $t, $u, $typ) {
      $ts = '' ;
      if ($typ == 'c' ) $ts = 'credit' ;
      if ($typ == 'd' ) $ts = 'debit' ;
      return " acc.get_$ts" . "balance( '$f', '$t', gd.coa,$u) " ;
   }
   function getCloseBal($c, $d, $u, $t,$acn) {
      global $data ;
      $q = "SELECT acc.get_closebal('$d', '$c', '$u', '$t','$acn'::char(16)) as cbl ;" ;
      $r=$data->selectSet($q) ;
//echo "<P>$q</P>" ;
      return ($r->f['cbl'] );
   }
   function getOpenBal($c, $d, $u, $t, $acn) {
      global $data ;
      $q = "SELECT acc.get_openbal('$d', '$c', '$u', '$t', $acn::char(16)) as obl ;" ;
      $r=$data->selectSet($q) ;
//echo "<P>$q</P>" ;
      return ($r->f['obl'] );
   }
exit;
?>
