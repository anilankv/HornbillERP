<?php
   include_once(getcwd() . '/ol/classes/db/Connection.php');
   $_connection = new Connection(
      $conf['dbhost'],
      $conf['dbport'],
      $d_g['un'],
      $d_g['op'],
      $conf['dbname'],
      $conf['dbms']
   );
   if ( $_connection->conn->_connectionID == false ) {
      $rA['__Error'] = "Old password Entered is Incorrect !! " ;
   } else {
      $data->getSelect( "select admin.passwd_change( '{$d_g['un']}', '{$d_g['np']}' );", $rA, 'S', __LINE__);
      $data->getSelect( "COMMIT", $rA, 'S', __LINE__);
      $rA['__Notice'] = "Password Changed successfully !!";
   }
?>
