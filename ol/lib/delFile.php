<?php
  if(check_srv_auth($uid_g, $ssrv_g, $gid_g ) != 1 ) return ;
  $cpth = $conf['upload_path'] ;
  $sdir = "\/" ;
  $sql =  "UPDATE log.fileupd SET sts=-1 WHERE id=$fno_g" ;
  $data->getSelect( $sql, $rA, 'D', __LINE__ ) ;
  $rA[0] = $fno_g ;
?>
