<?php
  if(check_srv_auth($uid_g, $ssrv_g, $gid_g ) != 1 ) return ;
  $fPst_g  = Unserializer(hdrfetch('pst-dt')) ;
  if (empty($_FILES{"fN"}{"name"})) return ;
  $sts = 0 ;
  $cpth = $conf['upload_path'] ;
  $sdir = "" ;
  $sql =  "SELECT p.op FROM sw.widprm p, sw.srv s WHERE s.id=$ssrv_g AND p.wnm='$wNm_g' AND p.snm=s.snm " ;
  $rs = $data->getSelect( $sql, $rA, 'F', __LINE__  ) ;
  $aDt = getdate() ;
  $dts = $aDt['year'] . str_pad($aDt['mon'], 2, '0', STR_PAD_LEFT) . str_pad($aDt['mday'], 2, '0', STR_PAD_LEFT) ;
  if (! ($rs->EOF) ){
     $opA = preg_split('/\|/', $rs->f['op']);
     $sdir = (empty($opA[0])) ? "/$dts/" : $opA[0] . "/$dts/" ; 
  }
  if (!is_dir($cpth . $sdir)) mkdir($cpth . $sdir, 0700, true);
  if ( $ftp_g == 'P' ) {
     $fN = 'Photo' ;
     $imDt = file_get_contents($_FILES["fN"]["tmp_name"]) ;
     $flDt=substr($imDt, strpos($imDt, ",")+1);
     $flDt = str_replace(' ', '+', $flDt);
     $uencDt=base64_decode($flDt);
     file_put_contents($cpth . $sdir . $fN . '.' .  $aDt[0] , $uencDt);
     $sts = 1 ;
  } else {
     $fN = $_FILES["fN"]["name"] ; 
     if(move_uploaded_file( $_FILES["fN"]["tmp_name"], $cpth . $sdir . $fN . '.' .  $aDt[0] )) $sts = 1 ; 
  }
  if ( $sts == 1 ) {
     $rs = $data->selectSet(" Begin ; " ) ;
     $sql =  "INSERT INTO log.fileupd (ssn, cpth,pth,fnm, fts, tm,srv, rmk, unt) VALUES ( $sid_g, '$cpth', '$sdir', '$fN', '{$aDt[0]}', now(), $ssrv_g, '$rmk_g', '$unt_g' ) returning id ;" ;
     $rA['__Q'] = $sql ;
     if($err = $data->getError()) $rA['__Error'] = (empty($rA['__Error'])) ? $err : $rA['__Error'] ; 
     $rs = $data->getSelect( $sql,  $rA, 'F', __LINE__  ) ;
     if (! ($rs->EOF) ){
        $rA[0] = $rs->f['id'] ;
        $rA[1] = $sdir ;
        $rA[2] = $fN ;
        $rA[3] = $rmk ;
        $rA[4] = 1 ;
        $rA[5] = empty($opA[1]) ? '' : $opA[1] ;
        $rA[6] = $opA[0] ;
        $rA[7] = $sdir ;
        $rA[8] = $aDt[0] ;
     }
     if (!empty($opA[1])) {
        include_once($root_path .'/scripts/' . $opA[1] . ".php" );
     }
     $data->selectSet( "Commit ;" ) ;
  }
?>
