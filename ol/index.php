<?php
  if(!( empty( $srvdt_g ))){
     ob_start();
     if( $srvdt_g['top'] != '' ) {
        include $root_path . '/scripts/' .  $srvdt_g['top'] . '.php' ; 
        $rA['top'] = ob_get_contents();
     }
     ob_clean();
     if( $srvdt_g['lft'] != '' ){
        include $root_path . '/scripts/' .  $srvdt_g['lft'] . '.php' ; 
        $rA['lft'] = ob_get_contents();
     }
     ob_clean();
     if( $srvdt_g['rgt'] != '' ){
        include $root_path . '/scripts/' .  $srvdt_g['rgt'] . '.php' ; 
        $rA['rgt'] = ob_get_contents();
     }
     ob_clean();
     if( $srvdt_g['bot'] != '' ){
        include $root_path . '/scripts/' .  $srvdt_g['bot'] . '.php' ; 
        $rA['bot'] = ob_get_contents();
     }
     ob_clean();
     if ( $srvdt_g['scf'] != 'f' ) {
        include $script_path . $srvdt_g['scp'] . '.php' ; 
     } else {
        $template->set_filenames(array( $srvdt_g['bdy'] => $srvdt_g['bdy'] . '.tpl'));
        $template->pparse($srvdt_g['bdy']);
     }
     $rA['bdy'] = ob_get_contents();
     ob_end_clean();
   
//     if(!empty( $mod_g) )if ( $mod_g == 'sav' ) {
//        if( $srvdt_g['stl'] != '' ) {
//           $qry = "UPDATE " . $srvdt_g['stl'] . " SET "  ;
//           if(( $srvdt_g['ufl'] != '' )  && ( $srvdt_g['tfl'] != '' )){
//              $qry += ' SET ' ;
//              if( $srvdt_g['ufl'] != '' ) $qry += $srvdt_g['ufl'] . "='$uid_g', " ;
//              if( $srvdt_g['tfl'] != '' ) $qry += $srvdt_g['tfl'] . "=now() " ;
//           }
//           $qry += " where id=" . "='$key_g'"  ;
//           $rs=$data->getSelect ( $qry, $rA, "index', __LINE__ );
//        }
// To do :  PopUp PG_ERROR, ROLLACK, COMMIT, 
//        $qry = "Insert into admin.log  values ('$srv_g','$key_g','$uid_g',now());" ;
//        $rs=$data->getSelect ( $qry, $rA, "index', __LINE__ );
//        $qry = "SELECT admin.delete_message( id ) from admin.msg_$gid_g where act=$srv_g and val = '$key_g'  " ;
//        $rs=$data->getSelect ( $qry, $rA, "index', __LINE__ );
//        $qry = "SELECT admin.set_next_message ( $srv_g, '$key_g', '$uid_g', '$gid_g') ; " ;
//        $rs=$data->getSelect ( $qry, $rA, "index', __LINE__ );
//     }
  } ;
  if (!(empty($rA))) echo "(" . json_encode($rA) . ")" ;
?>
