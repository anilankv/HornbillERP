<?php
  $ssrv= isset( $_GET['ssrv']) ? $_GET['ssrv'] : -1  ;
  $fno = isset( $_GET['fno']) ? $_GET['fno'] : -1  ;
  $dmd = empty( $_GET['dmd']) ? 'file' : $_GET['dmd'] ;
  $thumb = empty( $_GET['thumb']) ? 0 : 1 ;
  if(check_srv_auth($uid_g, $ssrv, $gid_g ) != 1 ) return ;
  $cpth = $conf['upload_path'] ;
  $sdir = "\/" ;
//  $uq = "  select e.nam || ', ' || g.nam || ', ' || d.dnm as pnm  from (select us.id, us.nam from auth.usr us where id in (select s.usr " ;
//  $uq .= " from log.ssn s, log.fileupd f where s.id = f.ssn and f.id=$fno )) q,org.employee e, org.post p, org.desig  g, org.dprt_det d, org.units u " ;
//  $uq .= " where e.eno=q.nam and e.pst=p.id  and p.dsg = g.id and p.dep= d.id and p.unt=u.id  ;" ;
//  $uRs = $data->getSelect( $uq, $rA, 'gF', __LINE__ ) ;
//  $pnm = is_object($uRs) ? $uRs->f['pnm'] : '' ;
  $sql =  "SELECT cpth || pth || COALESCE(fnm,'') || COALESCE('.' || fts ,'') as fn, COALESCE(fnm,'') as fnm FROM log.fileupd f WHERE f.id=$fno " ;
  $rA['fsql'] = $sql ;
  $rs = $data->getSelect( $sql, $rA, 'gF', __LINE__ ) ;
  if (! ($rs->EOF) ){
     $fn = $rs->f['fn'] ; 
     $fnm = $rs->f['fnm'] ; 
     if (file_exists($fn)) {
        $sfn = preg_replace('/\d*$/', '', basename($fnm));
        $sfn = preg_replace('/\.$/', '', $sfn);
        if($dmd == 'inline' ) {
          $c = file_get_contents($fn);
          $c = base64_encode($c); 
//          echo ('data:' . mime_content_type($fn) . ';base64,' . $c);
          echo ('data:' . getMimeTyp($fn) . ';base64,' . $c);
	} else if($dmd == 'pdf' ) {
           if ($thumb) {
              $im = new Imagick();
              $im->setResolution(30, 30); 
              $im->readImage($fn . "[0]");
              $im->setImageFormat('png');
              header('Content-Type: image/png');
              echo $im;
           } else {
              $c = file_get_contents($fn);
              //$c = base64_encode($c); 
//             echo ('data:' . mime_content_type($fn) . ';base64,' . $c);
              header('Content-Type:application/pdf');
              header('Content-Length: ' . filesize($fn));
              readfile($fn);
           }
        } else {
           header('Content-Description: File Transfer');
           header('Content-Type: ' . mime_content_type($fn));
//           header('Content-Type: ' . getMimeTyp($fn));
           header('Content-Disposition: attachment; filename='.$sfn);
           header('Expires: 0');
           header('Cache-Control: must-revalidate');
           header('Pragma: public');
           header('Content-Length: ' . filesize($fn));
           readfile($fn);
        }
//        exit;
     }
  }
  function getMimeTyp ($fn) {
    $fi = new finfo();
    if (is_resource($fi) === true) {
        return $fi->file($fn, FILEINFO_MIME_TYPE);
    }
    return false;
  }
?>
