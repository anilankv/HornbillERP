<?php
   if( !defined("OSS_INSTALLED") ) {
      define('OSS_INSTALLED', true);
   }
   $conf['appName'] = 'ദേശാഭിമാനി' ;
   $conf['dbms'] = 'postgres7';
   $conf['dbhost'] = '127.0.0.1';
   $conf['dbport'] = 5432 ;
   $conf['dbname'] = 'dbname';
   $conf['baseurl'] = "http://hornbill.yourdomain.com/" ;
   $conf['dbuser'] = 'pubusr';
   $conf['dbpasswd'] = 'pubusr';
   $conf['dbusrgrp'] = '0';
   $conf['table_prefix'] = '';
   $conf['description'] = 'oss' ;
   $conf['timezone'] = 'Asia/Calcutta' ; 
   $conf['upload_path'] = '/home/hornbill/fileUp/';
   $conf['MailUser'] = 'ewf@atps.in';
   $conf['DfltAddr'] = 'ewf@atps.in';
   $conf['MailPsw'] = 'vR!ZKBG6';
   $conf['smtpHost'] = 'smtp.atps.in' ;
   $conf['smtpPort'] = 587; //25 ; // 587 ;
   $conf['popHost'] = 'pop.atps.in' ;
   $conf['popPort'] = 110 ;
   $conf['servers'][0]['desc'] = 'Postgres'; // Display name for server
   $conf['servers'][0]['type'] = 'pg_oss'; // or my_oss
   $conf['servers'][0]['host'] = '127.0.0.1';  // Hostname or IP address for server.
   $conf['servers'][0]['port'] = 5432;         // Database port on server
   $conf['servers'][0]['defaultdb'] = 'dbname';  // Default database to connect to.
   $conf['servers'][0]['pg_dump_path'] = '/usr/bin/pg_dump';
   $conf['servers'][0]['pg_dumpall_path'] = '/usr/bin/pg_dumpall';
   $conf['extra_login_security'] = true;
   $conf['version'] = 1.0;
   $conf['dbg'] = 4;
?>
