<?php
   if( !(isset($_SESSION)))session_start();
   $lq = "UPDATE log.ssn SET ttm = now(), sts=0 WHERE id = '$sid_g' RETURNING id " ;
   $sid_g=$data->getSelect( $lq, $rA, 'S', __LINE__ )->f['id'] ;
   unset($_SESSION);
   session_destroy();
   header("Location: /index.php?f=1&srv=1001");
?>
