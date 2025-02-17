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
 *                              }
 *                              cnd {
 *                                         fld
 *                                         val
 *                              }
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
   $ssrv= isset( $_GET['ssrv']) ? $_GET['ssrv'] : -1  ;
   $t = empty( $_GET['typ']) ? '0' : $_GET['typ'] ;
   $fPst_g  = hdrfetch('pst-dt') ;
//print_r($dt ) ;
   $dt = $_POST['dt'] ;
   $oDt = json_decode( $dt ,true) ;
//print_r($oDt) ;
   // $frmUnt = hdrfetch('frmUnt:') ;
   $frmUnt = $hdr_g['frmUnt'] ;
   $toUnt =  $hdr_g['toUnt'] ;
   $toGrp =  $hdr_g['toGrp'] ;
   $frmUsr = $hdr_g['frmUsr'] ;
   $nxtSrv = $hdr_g['nxtSrv']  ;
   $dmlTyp = $hdr_g['dmlTyp']  ;
   $ent = $hdr_g['ent'] ;
   $kv = $hdr_g['kv'] ;
//echo "\n\n line " . __LINE__ . " frmUnt $frmUnt unm $unm_g uid $uid_g gid  $gid_g   \n \n" ;
    //  $data->selectSet( "BEGIN ;"  ) ;
   if ($t == 'j' ) {
      $q1 = "SELECT tbl, pk from sw.entity where cod = '$ent' " ;
      $rs1  = $data->getSelect($q1, $rA, 'S', __LINE__ );
      if($err = $data->getError()) $rA['__Error' . __LINE__] = (empty($rA['__Error' . __LINE__])) ? $err : $rA['__Error'] ; 
      if ($rs1->EOF) {
         $rA['__Notice'] = __FILE__ . " Invalid Request Type ($q1) " ;
         echo "(" . json_encode($rA) . ")" ;
         $data->selectSet( "ROLLBACK ;" ) ;
         exit ;
      } ;
      $tbl = $rs1->f['tbl'] ;
      $pk = $rs1->f['pk'] ;
      if( $dmlTyp == 'INSERT' ) {
         foreach ( $oDt['dt'] as $fld => $val ) {
            $val = (empty($val)) ? 'NULL' : "'$val'" ;
            $vStr = (empty($vStr)) ? '': $vStr . ", " ;
            $vStr .= $val ;
            $fStr = (empty($fStr)) ? '': $fStr . ", " ; 
            $fStr .= $fld ;
         } ;
         if (empty($fStr)) {
            $q2 = "select null" ;
         } else {
            $q2 = "INSERT INTO $tbl ($fStr) VALUES ( $vStr )" ;
         }
echo "\n line " . __LINE__ . " sql $q2 ;  \n" ;
      } else if( $dmlTyp == 'UPDATE' ) {
         foreach ( $oDt['dt'] as $fld => $val ) {
            $fStr = (empty($fStr)) ? '': $fStr . ", " ; 
            $cStr = (empty($cStr)) ? '': $cStr . " AND " ; 
            $val = (empty($val)) ? 'NULL' : "'$val'" ;
            $fStr .= " $fld = $val " ;
            $cStr .= ($val == 'NULL') ? " $fld IS $val " : " $fld = $val " ;
//echo "\n line " . __LINE__ . " sql $q2 ;  \n" ;
         } ;
         if (empty($fStr)) {
            $q2 = "select null" ;
         } else {
            $q2 =  "UPDATE $tbl SET $fStr WHERE $pk = '$kv' " ;
            if (!empty($cStr)) $q2 .= " AND $cStr " ;
         }
//echo "\n line " . __LINE__ . " sql $q2 ;  \n" ;
      } else if( $dmlTyp == 'DELETE' ) {
         foreach ( $oDt['dt'] as $fld => $val ) {
            $cStr = (empty($cStr)) ? '': $cStr . " AND " ; 
            $val = (empty($val)) ? 'NULL' : "'$val'" ;
            $cStr .= ($val == 'NULL') ? " $fld IS $val " : " $fld = $val " ;
         } ;
         $q2 = "DELETE FROM $tbl WHERE $pk = '$kv' " ;
         if (!empty($cStr)) $q2 .= " AND $cStr " ;
      } ;
      $rs2  = $data->getSelect($q2, $rA, 'S', __LINE__ );
      if ( !is_object($rs2)) {
         if($err = $data->getError()) $rA['__Error' . __LINE__] = (empty($rA['__Error' . __LINE__])) ? $err : $rA['__Error'] ; 
         echo "(" . json_encode($rA) . "($q2))" ;
         $data->selectSet( "ROLLBACK ;" ) ;
         exit ;
      } ;
echo "\n line " . __LINE__ . " main sql $q2 ; \n" ;
//print_r($oDt['subtbl']) ;
      foreach ( $oDt['subtbl'] as $tbl => $tDt ) {
         foreach ( $tDt['rw'] as $row => $rwD ) {
            $vStr = '' ;
            $fStr = '' ;
            $sStr = '' ;
            $cStr = '' ;
            foreach ( $rwD['cnd'] as $fld => $val ) {
               $cStr = (empty($cStr)) ? '': $cStr . " AND " ; 
               $cStr .= ($val == 'NULL') ? " $fld IS $val " : " $fld = $val " ;
            }
//echo "\n line " . __LINE__ . " subtbl $tbl [$row] where $cStr  \n" ;
            foreach ( $rwD['dt'] as $fld => $val ) {
               $val = (empty($val)) ? 'NULL' : "'$val'" ;
               $vStr = (empty($vStr)) ? '': $vStr . ", " ;
               $vStr .= $val ;
               $fStr = (empty($fStr)) ? '': $fStr . ", " ; 
               $fStr .= $fld ;
               $sStr = (empty($sStr)) ? '': $sStr . ", " ; 
               $sStr .= " $fld = $val" ;
//echo "\n@" . __LINE__ . " fld ($fld) val ($val)  cStr ($cStr) tbl ($tbl) pk ($pk) kv ($kv) \n" ;
            };
            if( $dmlTyp == 'INSERT' ) {
               if (empty($fStr)) {
                  $q3 = "select null" ;
               } else {
                  $q3 = "INSERT INTO $tbl ($fStr) VALUES ( $vStr )" ;
               }
//echo "\n line " . __LINE__ . " chksql $q5 ; " ; //  fstr ($fStr) cStr ($cStr) tbl ($tbl) pk ($pk) kv ($kv) \n" ;
            } else if( $dmlTyp == 'UPDATE' ) {
               if (empty($fStr)) {
                  $q3 = "select null" ;
               } else {
                  $q5 = "SELECT * FROM $tbl WHERE $cStr " ;
//echo "\n line " . __LINE__ . " chksql $q5 ; " ; //  fstr ($fStr) cStr ($cStr) tbl ($tbl) pk ($pk) kv ($kv) \n" ;
                  $rs5 = $data->getSelect($q5, $rA, 'S', __LINE__ ) ;
                  if ($rs5->EOF){
                     $q3="INSERT INTO $tbl ($fStr) values ($vStr) ";
                  } else{
                     $q3 = "UPDATE $tbl SET $sStr  WHERE $cStr " ;
                  }
               } 
            } else if( $dmlTyp == 'DELETE' ) {
               if (!empty($cStr)) { ;
                  $q3 = "DELETE FROM $tbl WHERE $cStr " ;
               }
            }
echo "\n line " . __LINE__ . " sub sql ( $q3 ; ) \n" ;
            $rs3  = $data->getSelect($q3, $rA, 'S', __LINE__ );
            if ( !is_object($rs3)) {
               if($err = $data->getError()) $rA['__Error' . __LINE__] = (empty($rA['__Error' . __LINE__])) ? $err : $rA['__Error'] ; 
               echo "(" . json_encode($rA) . "($q3))" ;
               $data->selectSet( "ROLLBACK ;") ;
               exit ;
            } ;
         };
      };
   } else if ($t == 'f' ) {
//echo "<p>\n File Received \n\n" ;
      $aDt = getdate () ;
      $dts = $aDt['year'] . str_pad($aDt['mon'], 2, '0', STR_PAD_LEFT) . str_pad($aDt['mday'], 2, '0', STR_PAD_LEFT) ;
      $cpth = $conf['upload_path'] ;
      $sdir = "" ;
      if (!is_dir($cpth . $sdir)) mkdir($cpth . $sdir, 0700, true);
      foreach ( $_FILES as $fnd => $fI ) {
         $sts = 0 ;
         $tA = explode ('||', $fnd) ;
         $fno = $tA[0] ;	 
         $fts = $tA[1] ;	 
         $fN = $fI["name"] ; 
         if(move_uploaded_file ( $fI["tmp_name"], $cpth . $sdir . $fN . '.' .  $fts )) $sts = 1 ; 
         if ( $sts == 1 ) {
            $q4 =  "INSERT INTO log.fileupd (id, ssn, cpth,pth,fnm, fts, tm,srv, rmk, unt) VALUES ( '$fno', $sid_g, '$cpth', '$sdir', '$fN', '{$aDt[0]}', now(), $ssrv, 'Transferred file' , '$frmUnt' ) returning id ;" ;
echo "\n " . __FILE__ . "@" . __LINE__ . " $q4 ; \n" ;
            $rs4 = $data->getSelect( $q4, $rA, 'S', __LINE__  ) ;
            if ( !is_object($rs4)) {
               if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
               echo "(" . json_encode($rA) . "($q4))" ;
               $data->selectSet( "ROLLBACK ;" ) ;
               exit ;
            } ;
            if (! ($rs4->EOF) ){
               $rA[0] = $rs4->f['id'] ;
               $rA[1] = $sdir ;
               $rA[2] = $fN ;
               $rA[3] = $rmk ;
               $rA[4] = 1 ;
               $rA[5] = empty($opA[1]) ? '' : $opA[1] ;
               $rA[6] = $opA[0] ;
               $rA[7] = $sdir ;
               $rA[8] = $aDt[0] ;
            }
         }
      } ;
   }
   if ($t == 'j' ) {
      $q5 = "SELECT log.create_next_msg ($nxtSrv::int,'$sid_g'::int,$kv::varchar ,  $toGrp::int, 'test data sync'::text, '$frmUnt'::varchar, '$aS_g'::varchar,'$toUnt'::varchar ) ; " ;
echo "\n " . __FILE__ . "@" . __LINE__ . " $q5 ; \n" ;
      $rs5 =$data->getSelect ( $q5, $rA, 'S', __LINE__  );
      if ( !is_object($rs5)) {
         if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
         $rA['__Notice'] = $data->getNotice() ;
         echo "(" . json_encode($rA) . " ( $q5 ;  ))" ;
         $data->selectSet( "ROLLBACK ;" ) ;
         exit ;
      } ;
   } ;
 //  $data->selectSet( "COMMIT ;" ) ;
?>
