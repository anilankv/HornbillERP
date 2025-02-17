<?php
   $sNm = isset( $_GET['sNm']) ? $_GET['sNm'] : ''  ;
   $sEv = isset( $_GET['sEv']) ? $_GET['sEv'] : ''  ;
   if ( check_auth( $sNm, $sEv ) == false ){
      echo ("You are not authorise to perform this operation ($sEv) on ($sNm) ")  ;
      return ;
   } ;
   $rA['__Error'] = ($err = $data->getError()) ? $err : '(0)' ; 
   $ssrv= empty( $_GET['ssrv']) ? -1 : $_GET['ssrv']  ;
   $wnm= empty( $_GET['wnm']) ? '' : $_GET['wnm']  ;
   $vno= empty( $_GET['vno']) ? '-1' : $_GET['vno'] ;
   $mod='PDF';
   $k_g = ( $key_g == '' ) ? 'null' : "'$key_g'" ; 
   $rs = $data->selectSet( "BEGIN " ) ;
//   if($err = $data->getError()) $rA['__Error'] .=  ' |a| ' . $err  ; 
   ob_start();
   if ( $srvdt_g['scf'] != 'f' ) {
      if(!empty($srvdt_g['scp']))include $script_path . $srvdt_g['scp'] . '.php' ; 
   } else {
     $template->set_filenames(array( $srvdt_g['bdy'] => $srvdt_g['bdy'] . '.tpl'));
     $template->pparse($srvdt_g['bdy']);
   }
   $htm = ob_get_contents();
   ob_clean();
   ob_end_clean();
//   require_once( getcwd() . '/ol/classes/dompdf/autoload.inc.php');
//   use Dompdf\Adapter\CPDF;
//   use Dompdf\Dompdf;
//   use Dompdf\Exception;
////   $rA['htm'] = $htm ;
//   if (!empty($htm)) {
//      $dp=new Dompdf();
//      $dp->set_base_path($root_path);
//      $dp->setPaper('A4', 'portrait');
//      $dp->load_html($htm);
//      $dp->render();
///////////////////////////////////////////////////////////////////////////
    $dspc = array(
        0 => array('pipe', 'r'), // stdin
        1 => array('pipe', 'w'), // stdout
        2 => array('pipe', 'w'), // stderr
       );
    $proc = proc_open('xvfb-run  -e ~/log/xvfb_e  --auto-servernum  wkhtmltopdf -B 50mm -T 50mm -q - - ', $dspc, $p);
    //$proc = proc_open('date ', $dspc, $p);
    fwrite($p[0], $htm);
    fclose($p[0]);
    $pdf = stream_get_contents($p[1]);
    $err = stream_get_contents($p[2]);
    // Close the process
    fclose($p[1]);
    $rtn = proc_close($proc);
    // Output the results
    if ($err) {
       $rs = $data->selectSet( "ROLLBACK ;" ) ;
       $rA['__Error'] = 'PDF Error: ' . $err ;
       echo "(" . json_encode($rA) . ")" ;
       //exit ;
       throw new Exception('PDF Error: ' . $err);
    } 
    $sts = 0 ;
    $cpth = $conf['upload_path'] ;
    $sdir = "" ;
    $sql =  "SELECT p.op FROM sw.widprm p, sw.srv s WHERE s.id=$ssrv AND p.wnm='$wnm' AND p.snm=s.snm " ;
    $rs = $data->getSelect( $sql, $rA, 'PDF', __LINE__ ) ;
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
    if ( $mod == 'PDF' ) {
       $fN = 'PDF.pdf' ;
       file_put_contents( "$cpth$sdir" . '/' . "$fN.$tS" , $pdf);
       $sts = 1 ;
    }
    $fno_g = '-1' ;
    if ( $sts == 1 ) {
       if (!empty($opA[1])) {
          include_once($root_path .'/scripts/' . $opA[1] . ".php" );
       }
       $sql =  "INSERT INTO log.fileupd (ssn, cpth,pth,fnm, fts, tm,srv, rmk, unt) VALUES ( $sid_g, '$cpth', '$sdir', '$fN', '$tS', now(), $ssrv, 'PDF creation', '$unt_g' ) returning id ;" ;
       $rs = $data->getSelect( $sql, $rA, 'PDF', __LINE__ ) ;
       if ( !is_object($rs)) {
          if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
          $rA['__Notice'] = $data->getNotice() ;
          $rA['_Eqry']     = $sql;
          echo "(" . json_encode($rA) . ")" ;
          $rs = $data->selectSet( "ROLLBACK ;" ) ;
          exit ;
       } else if( is_object($rs)) $fno_g = $rs->f['id'] ;
    }
    $rA['fN'] = $fno_g ;
    $fn = "$cpth$sdir" . '/' . "$fN.$tS.pdf" ;
///////////////////////////////////////////////////////////////////////////
//    header('Content-Type: application/pdf');
//    header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
//    header('Pragma: public');
//    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//    header('Last-Modified: ' . gmdate('D, d M Y H:i:s').' GMT');
//    header('Content-Length: ' . strlen($pdf));
//    header('Content-Disposition: inline; filename=' . $fN );
//    echo $pdf;
///////////////////////////////////////////////////////////////////////////
//      $c = file_get_contents($fn);
//      $c = base64_encode($c); 
//      $rA['fC'] = base64_encode($c) ;
//      $rA['fS'] = filesize($fn) ;
//      $rA['fN'] = "$fN.pdf" ;
//      $rA['mime'] = mime_content_type($fn) ;
//      echo ('data:' . mime_content_type($fn) . ';base64, ' . $c);
//      echo ('data:' . getMimeTyp($fn) . ';base64,' . $c);
//      header('Content-Description: File Transfer');
//      header('Content-Type: ' . mime_content_type($fn));
//      header("Content-Disposition: attachment; filename=$fN.pdf");
//      header('Expires: 0');
//      header('Cache-Control: must-revalidate');
//      header('Pragma: public');
//      header('Content-Length: ' . filesize($fn));
//      if (count($rA)) echo "(" . json_encode($rA) . ")" ;
//      readfile($fn);
//      echo base64_encode($c) ;
      $sql = "SELECT * from sw.exeq WHERE snm='$sNm' and ord='$vno' and typ = 'N' " ;
      $rs = $data->getSelect( $sql, $rA, 'PDF', __LINE__ ) ;
      if ( !is_object($rs)) {
         if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
         $rA['__Notice'] = $data->getNotice() ;
         $rA['_Eqry']     = $sql;
         echo "(" . json_encode($rA) . ")" ;
         $rs = $data->selectSet( "ROLLBACK ;" ) ;
         exit ;
      } else if ( !($rs->EOF)) {
         $vno = $rs->f['ord'] ;
         $rV_g[$vno] =  empty($rV_g[$vno]) ? array() : $rV_g[$vno];
         $pat = "/" . '\$fno_g' . "/" ;
         $qry = preg_replace( $pat, $fno_g,$rs->f['str']  ) ;
         $exDt  = fetch() ;
         $qry = getQryFrmPrimitive( $qry, $exDt)  ;
         $rs = $data->getSelect( $qry, $rA, 'PDF', __LINE__ ) ;
//       $rA['_qry'] .=  "($i)[ $qry ]" ;
//       if($err = $data->getError()) $rA['__Error'] .=  ' |4| ' . $err  ; 
         $sid = ( $sid_g == -1 ) ? 'null' : "'$sid_g'" ;
         $lq = "INSERT INTO log.adl (sid, srv, tim, qry ) VALUES ( $sid, '$aS_g', now(), '" . preg_replace( "/'/", "''" , $qry )   . "' ) " ; 
         $data->getSelect( $lq, $rA, 'PDF', __LINE__ ) ;
         if ( !is_object($rs)) {
            $rs = $data->selectSet( "ROLLBACK ;" ) ;
//          if($err = $data->getError()) $rA['__Error'] .=  ' |5| ' . $err  ; 
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
//       if($err = $data->getError()) $rA['__Error'] .=  ' |6| ' . $err  ; 
      }
//   } else {
//      $rA['__Error'] =  'Blank Document Ignored ' ; 
//   }
   $data->selectSet( "COMMIT " ) ;
   if (count($rA)) echo "(" . json_encode($rA) . ")" ;
?>
