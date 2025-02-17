<?php
   $rP_g = $_SERVER['REDIRECT_URL'] ;
   $rH_g = $_SERVER['HTTP_HOST'] ;
   $vA_g = preg_split('/\//', $rP_g);
   include_once( getcwd() . '/index.php');
?>
