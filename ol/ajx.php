<?php
   $k_g = ( $key_g == '' ) ? 'null' : "'$key_g'" ; 
//   $rs = $data->selectSet( "BEGIN ;" ) ;
   if ( $srvdt_g['scf'] != 'f' ) {
//      include $lib_path . $srvdt_g['scp'] . '.php' ; 
//   } else if ( $srvdt_g['scf'] == 's' ) {
      if(!empty($srvdt_g['scp']))include $script_path . $srvdt_g['scp'] . '.php' ; 
   } else {
     ob_start();
     $template->set_filenames(array( $srvdt_g['bdy'] => $srvdt_g['bdy'] . '.tpl'));
     $template->pparse($srvdt_g['bdy']);
     $rA['bdy'] = ob_get_contents();
     ob_end_clean();
   }
//   $data->selectSet( "COMMIT ;" ) ;
   if(empty($dmd) ) if (count($rA)) echo "(" . json_encode($rA) . ")" ;
?>
