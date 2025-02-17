<?php
/***************
 * postDt {
 *         frmUnt
 *         toUnt
 *         toGrp
 *         frmUsr
 *         nxtSrv
 *         ent
 *         kv
 *         dt {
 *                 nam
 *                 val
 *         }
 *      dmlTyp
 *         subtbl {
 *                 tbl
 *                         rwDt {
                                dml
 *                                 dt {
 *                                         val
 *                                         fld
 *                                 }
 *                         }
 *                         cnd {
 *                                 fld
 *                                 val
 *                        }
 *                 }
 *         }
 *         flst {
 *                 fno
 *                 pth
 *                 dt
 *         }
 * }
 *
 *******************/
   $pDt  = fetch() ;      
   $oDt  = array() ;
   $fLst = array() ;
   $p  = 0 ;
   $kv = $pDt['kv'] ;
   $oDt['frmUnt'] = $unt_g ;
   $oDt['toUnt']  = $pDt['toUnt'] ;
   $oDt['toGrp']  = $pDt['toGrp'] ;
   $oDt['frmUsr'] = $pDt['frmUsr'] ;
   $oDt['nxtSrv'] = $pDt['nxtSrv'] ;
   $oDt['dmlTyp'] = empty($pDt['dmlTyp'])? 'INSERT' : $pDt['dmlTyp'] ;
   $oDt['ent']    = $pDt['ent'] ;
   $oDt['kv']     = $kv ;
   $oDt['fld']    = $pDt['kv'] ;
   $q1  = "SELECT tbl, pk from sw.entity where cod = '{$pDt['ent']}' " ;
   $rs1 = $data->getSelect($q1, $rA, 'S', __LINE__ );
   if ($rs1->EOF) {
      $rA['__Notice'] = __FILE__ . " Invalid Request Type ($q1) "  ;
      echo "(" . json_encode($rA) . ")" ;
      exit ;
   } ;
   $tbl = $rs1->f['tbl'] ;
   $pk  = $rs1->f['pk'] ;
   $q2  = "SELECT * from {$rs1->f['tbl']} t where t.$pk  = '$kv' " ;
   $rs2 = $data->getSelect($q2, $rA, 'S', __LINE__ );
//echo __FILE__ . " (($q1)) " .__LINE__ ;
   if ( !is_object($rs2)) {
      if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
      echo "(" . json_encode($rA) . " ($q2))" ;
      exit ;
   } ;
   $oDt['dt'] = $rs2->f ;
   $fflA = explode(',', $rs1->f['ffl']) ;
   foreach ($fflA as $fld ) {
      if(!empty($rs2->f[$fld])){
         $fLst[$p] = $rs2->f[$fld] ;
         $p++ ;
      }
   }
//print_r($rs2->f) ;   
   $q5="select url, usr, pwd, srv  from org.units where id='{$pDt['toUnt']}' ";
//echo __FILE__ . " @" . __FILE__ . " $q5 \n" ;
   $rs5 = $data->getSelect($q5, $rA, 'S', __LINE__ );
   $url =$rs5->f['url'];
   $usr =$rs5->f['usr'];
   $pwd =$rs5->f['pwd'];
   $csrv=$rs5->f['srv'];
   $ch  = curl_init();
   $oDt['subtbl'] = array() ;
   $q3  = "SELECT tbl, k1, rk1, k2, rk2, k3, rk3, ffl from sw.ent_dtl where pen = '{$pDt['ent']}' " ;
//echo __FILE__ . " (($q3)) " .__LINE__ ;
   $rs3 = $data->getSelect($q3, $rA, 'S', __LINE__ );
   if ( !is_object($rs3)) {
      if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
      echo "(" . json_encode($rA) . "($q3) )" ;
      exit ;
   } ;
   while (! ($rs3->EOF) ){
//print_r($rs3->f) ;
      $stbl= $rs3->f['tbl'] ;
      $k1  = $rs3->f['k1'] ;
      $rk1 = $rs3->f['rk1'] ;
      $k2  = $rs3->f['k2'] ;
      $rk2 = $rs3->f['rk2'] ;
      $k3  = $rs3->f['k3'] ;
      $rk3 = $rs3->f['rk3'] ;
      $fflA = explode(',', $rs3->f['ffl']) ;
//print_r($fflA) ;
      $oDt['subtbl'][$stbl] = array() ;
      //$oDt['subtbl'][$stbl]['cnd'] = array() ;
      $oDt['subtbl'][$stbl]['rw'] = array() ;
      $q4  = "SELECT s.* from $stbl s, $tbl t where  t.$pk  = '$kv' and s.$k1   = t.$rk1 " ;
      $q4 .= (!empty($k2) && !empty($rk2)) ? " and s.$k2 = t.$rk2 " : '' ; 
      $q4 .= (!empty($k3) && !empty($rk3)) ? " and s.$k3 = t.$rk3 " : '' ; 
      $rs4 = $data->getSelect($q4, $rA, 'S', __LINE__ );
      if ( !is_object($rs4)) {
         if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
         echo "(" . json_encode($rA) . " ($q4))" ;
         exit ;
      } ;
//echo __FILE__ . " (($q4)) " .__LINE__ ;
      $rCnt = 0 ;
      while (! ($rs4->EOF) ){
         //$oDt['subtbl'][$stbl]['cnd'][$k1] = $rs2->f[$rk1] ;
         $oDt['subtbl'][$stbl]['rw'][$rCnt] = array() ;
         $oDt['subtbl'][$stbl]['rw'][$rCnt]['dt'] = $rs4->f ;
         $oDt['subtbl'][$stbl]['rw'][$rCnt]['cnd'] = array() ;
//print_r($rs4->f) ;
         foreach ( $rs4->f as $fld => $val ){
            if ($fld == $k1 ) $oDt['subtbl'][$stbl]['rw'][$rCnt]['cnd'][$k1] = $val ;
            if ($fld == $k2 ) $oDt['subtbl'][$stbl]['rw'][$rCnt]['cnd'][$k2] = $val ;
            if ($fld == $k3 ) $oDt['subtbl'][$stbl]['rw'][$rCnt]['cnd'][$k3] = $val ;
         }
         $rCnt++ ;
         foreach ($fflA as $fld ) {
            if(!empty($rs4->f[$fld])){
               $fLst[$p] = $rs4->f[$fld] ;
               $p++ ;
            }
         }
         $rs4->MoveNext();
      }
      $rs3->MoveNext();
   }
   $sFlst = array() ;
   $fI = finfo_open(FILEINFO_MIME);
   foreach ($fLst as $p => $fno){
      $q6 = "SELECT cpth,pth,fts, COALESCE(fnm,'') as fnm,cpth || pth || COALESCE(fnm,'') || COALESCE('.' || fts ,'') as fn FROM log.fileupd f WHERE f.id=$fno " ;
//echo __FILE__ . " @" . __FILE__ . " $q6 \n" ;
      $rs6 = $data->getSelect( $q6, $rA, 'S', __LINE__  ) ;
      if ( !is_object($rs6)) {
         if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ;
         echo "(" . json_encode($rA) . ")" ;
         exit ;
      }
      $fn = $rs6->f['fn'] ;
      if (file_exists($fn)) {
         $mT = finfo_file($fI, $fn);
         if ($mT) {
            $tA = explode(";", $mT);
            $mT = $tA[0];
         }
         $s = "$fno||" . $rs6->f['fts'] ;
         $sFlst[$s] = curl_file_create($fn, $mT, $rs6->f['fnm']) ;
      }
   }
   finfo_close($fI);
   $hdr = array(
       "frmUnt"  . ":" .  $oDt['frmUnt'],
       "toUnt"   . ":" .  $oDt['toUnt'],
       "toGrp"   . ":" .  $oDt['toGrp'],
       "frmUsr"  . ":" .  $oDt['frmUsr'],
       "nxtSrv"  . ":" .  $oDt['nxtSrv'],
       "ent"     . ":" .  $oDt['ent'],
       "un"      . ":" .  $usr,
       "pw"      . ":" .  $pwd,
       "kv"      . ":" .  $kv,
       "dmlTyp"  . ":" .  $oDt['dmlTyp']
   ) ;
//echo __FILE__ . " @ " . __LINE__ .  " Url $url  Srv $csrv \n" ; 
//print_r($oDt) ;
   curl_setopt($ch, CURLOPT_URL, "https://" . $url . "/index.php?f=3&typ=j&srv=" .$csrv   );    // set url
//echo __FILE__ . " @ " . __LINE__ .  " Url ". $url . "/index.php?f=3&typ=j&srv=$csrv \n"   ; 
   curl_setopt($ch, CURLOPT_POST, TRUE);  
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false );
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, 'HornbillERP CURL' ) ;
   curl_setopt($ch, CURLOPT_HTTPHEADER, $hdr);
   curl_setopt($ch, CURLOPT_POSTFIELDS, "dt="  .  json_encode($oDt) . ' ' );  
   $rtn = curl_exec($ch); // $output conitains the output string
echo __FILE__ . " @ " . __LINE__ .  " user ($usr) url ($url) csrv ($csrv) \nOutput from receive </br>\n"   ; 
print_r($rtn);      
echo "\n</br>Output ends \n</br>" . __FILE__ . " @ " . __LINE__  ; 
   $error = curl_error($ch);
   $hdr[] = "Content-Type:multipart/form-data" ;

   $url1 = "https://" . $url . "/index.php?f=3&typ=f&srv=$csrv" ; // set url
   curl_setopt($ch, CURLOPT_URL, $url1 );    // set url
   curl_setopt($ch, CURLOPT_POST, TRUE);  
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false );
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, 'HornbillERP CURL' ) ;
   curl_setopt($ch, CURLOPT_POSTFIELDS, $sFlst );
   curl_setopt($ch, CURLOPT_HTTPHEADER, $hdr);
   curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
   $rtn = curl_exec ($ch);
echo __FILE__ . " @ " . __LINE__ .  " user ($usr) url ($url) csrv ($csrv) \nOutput from receive </br>\n"   ; 
print_r($rtn);      
echo "\n</br>Output ends \n</br>" . __FILE__ . " @ " . __LINE__  ; 
     curl_close($ch); // close curl resource to free up system resources
//echo "\nreturned (  )  (   $rtn   )"  ;
?>

