<?php
   if ( !defined('IN_OSS') ) {
      die('Hacking attempt');
   }
   $template->set_filenames(array(
//      'overall_footer' => ( empty($gen_simple_header) ) ?'overall_footer.tpl':'simple_footer.tpl')
      'overall_footer' => ( empty($gen_simple_header) ) ?'simple_footer.tpl':'simple_footer.tpl')
   );
   
   $template->assign_vars(array(
      'OSS_VERSION' => '2' . 1,
      'TRANSLATION_INFO' => ( isset($lang['TRANSLATION_INFO']) ) ? $lang['TRANSLATION_INFO'] : '', 
      'ADMIN_LINK' => "")
   );
   
   $template->pparse('overall_footer');
   
   if ( $do_gzip_compress ) {
      $gzip_contents = ob_get_contents();
      ob_end_clean();
   
      $gzip_size = strlen($gzip_contents);
      $gzip_crc = crc32($gzip_contents);
   
      $gzip_contents = gzcompress($gzip_contents, 9);
      $gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);
   
      echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
      echo $gzip_contents;
      echo pack('V', $gzip_crc);
      echo pack('V', $gzip_size);
   }
   exit;
?>
