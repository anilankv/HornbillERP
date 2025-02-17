<?php
   $sNm = isset( $_GET['sNm']) ? $_GET['sNm'] : ''  ;
   $sEv = isset( $_GET['sEv']) ? $_GET['sEv'] : ''  ;
   $ssrv= empty( $_GET['ssrv']) ? -1 : $_GET['ssrv']  ;
   $wnm= empty( $_GET['wnm']) ? '' : $_GET['wnm']  ;
   $vno= empty( $_GET['vno']) ? '-1' : $_GET['vno'] ;
   $mod= empty( $_GET['mod']) ? 'txt' : $_GET['mod'] ;
   $k_g = ( $key_g == '' ) ? 'null' : "'$key_g'" ; 
   $rs = $data->selectSet( "BEGIN " ) ;
   $rs = $data->getSelect ( "BEGIN ", $rA, 'I', __LINE__ );
   ob_start();
   if ( $srvdt_g['scf'] != 'f' ) {
      if(!empty($srvdt_g['scp']))include $script_path . $srvdt_g['scp'] . '.php' ; 
   } else {
     $template->set_filenames(array( $srvdt_g['bdy'] => $srvdt_g['bdy'] . '.tpl'));
     $template->pparse($srvdt_g['bdy']);
   }
   $htm = '' ;
   $htm = ob_get_contents();
   ob_clean();
   ob_end_clean();
   $dspc = array(
        0 => array('pipe', 'r'), // stdin
        1 => array('pipe', 'w'), // stdout
        2 => array('pipe', 'w'), // stderr
       );
   // $proc = proc_open('xvfb-run -e ~/log/xvfb_e --auto-servernum  wkhtmltopdf -q - - ', $dspc, $p);
   $proc = proc_open('cat - - ', $dspc, $p);
   fwrite($p[0], $htm);
   fclose($p[0]);
   $pdf = stream_get_contents($p[1]);
   $err = stream_get_contents($p[2]);
   fclose($p[1]);
   $rtn = proc_close($proc);
   if ($err) {
      $rs = $data->getSelect ( "ROLLBACK ", $rA, 'I', __LINE__ ) ;
      $rA['__Error'] = 'PDF Error: ' . $err ;
      echo "(" . json_encode($rA) . ")" ;
      throw new Exception('PDF Error: ' . $err);
   } 
   $sts = 0 ;
   $cpth = $conf['upload_path'] ;
   $sdir = "" ;
   $sql =  "SELECT p.op FROM sw.widprm p, sw.srv s WHERE s.id=$ssrv AND p.wnm='$wnm' AND p.snm=s.snm " ;
   $rs = $data->getSelect( $sql, $rA, 'AJXFILE', __LINE__ ) ;
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
   $fN = $mod . "_" . $tS . '.' .  $mod ;
   $sfN = "$cpth$sdir" . '/' . $fN ; 
   file_put_contents( $sfN, $pdf);
   $sts = 1 ;
   header('Content-Description: File Transfer');
   header('Content-Type: ' . mime_content_type($fN));
   header("Content-Disposition: attachment; filename=$fN");
   header('Expires: 0');
   header('Cache-Control: must-revalidate');
   header('Pragma: public');
   header('Content-Length: ' . filesize($sfN));
   readfile($sfN);

   $fno_g = '-1' ;
   if ( $sts == 1 ) {
      if (!empty($opA[1])) {
         include_once($root_path .'/scripts/' . $opA[1] . ".php" );
      }
      $sql =  "INSERT INTO log.fileupd (ssn, cpth,pth,fnm, fts, tm,srv) VALUES ( $sid_g, '$cpth', '$sdir', '$fN.pdf', '$tS', now(), $ssrv ) returning id ;" ;
      $rA['fluQ'] = $sql ;
      $rs = $data->getSelect( $sql, $rA, 'AJXPDF', __LINE__ ) ;
      if( is_object($rs)) $fno_g = $rs->f['id'] ;
      $sql = "SELECT * from sw.exeq WHERE snm='$sNm' and ord='$vno' and typ = 'N' " ;
      $rs = $data->getSelect( $sql, $rA, 'AJXPDF', __LINE__ ) ;
      if ( !($rs->EOF)) {
         $vno = $rs->f['ord'] ;
         $rV_g[$vno] =  empty($rV_g[$vno]) ? array() : $rV_g[$vno];
         $pat = "/" . '\$fno_g' . "/" ;
         $qry = preg_replace( $pat, $fno_g,$rs->f['str']  ) ;
         $exDt  = fetch() ;
         $qry = getQryFrmPrimitive( $qry, $exDt)  ;
         $rs = $data->getSelect( $qry, $rA, 'AJXPDF', __LINE__ ) ;
         $rA['_qry'] .=  "($i)[ $qry ]" ;
         $sid = ( $sid_g == -1 ) ? 'null' : "'$sid_g'" ;
         $lq = "INSERT INTO log.adl (sid, srv, tim, qry ) VALUES ( $sid, '$aS_g', now(), '" . preg_replace( "/'/", "''" , $qry )   . "' ) " ; 
         $data->getSelect( $lq, $rA, 'AJXPDF', __LINE__ ) ;
         if ( !is_object($rs)) {
            $rs = $data->selectSet( "ROLLBACK ;" ) ;
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
         if($err = $data->getError()) $rA['__Error'] .=  ' |6| ' . $err  ; 
      }
   }
   $data->selectSet( "COMMIT " ) ;
//   if (count($rA)) echo "(" . json_encode($rA) . ")" ;
?>
