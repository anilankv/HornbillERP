<?php
  function strip_html( $fstr ) {
     $fstr = preg_replace( "/\r\n/", ' ', $fstr ) ;
     $fstr = preg_replace( "/\n/", ' ', $fstr ) ;
     $fstr = preg_replace( "/\r/", ' ', $fstr ) ;
     $fstr = preg_replace( "/<\/?html[^>]*>/i", "\n", $fstr ) ;
     $fstr = preg_replace( "/<\/?body[^>]*>/i", "\n", $fstr ) ;
     $fstr = preg_replace( "/\&nbsp\;/", ' ', $fstr ) ;
     $fstr = preg_replace( "/<em>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\/em>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\?xml:[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\/?st1:[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\/?[a-z]\:[^>]*>/", '', $fstr ) ;
     $fstr = preg_replace( "/<\/?font[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\/?span[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\/?div[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\/?pre[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\/?h[1-6][^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\![^>]*>/", '', $fstr ) ;
     $fstr = preg_replace( "/<meta[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<style[^>]*>/i", '<style ', $fstr ) ;
     $fstr = preg_replace( "/<style[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<title[^>]*>/i", '<title ', $fstr ) ;
     $fstr = preg_replace( "/<title[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<p[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<\/p[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<ol[^>]*>/i", '<ul>', $fstr ) ;
     $fstr = preg_replace( "/<\/ol[^>]*>/i", '</ul>', $fstr ) ;
     $fstr = preg_replace( "/<strong[^>]*>/i", '<b>', $fstr ) ;
     $fstr = preg_replace( "/<\/strong[^>]*>/i", ' ', $fstr ) ;
     $fstr = preg_replace( "/<b><\/b>/i", ' ', $fstr ) ;
     $fstr = preg_replace( "/<\/b[^>]*>/i", ' ', $fstr ) ;
     $fstr = preg_replace( "/<\/?i[^>]*>/i", '', $fstr ) ;
     $fstr = preg_replace( "/<br[^>]*>/i", ' ', $fstr ) ;
     $fstr = preg_replace( "/  /", ' ', $fstr ) ;
     $fstr = preg_replace( "/<ul[^>]*>/i", "\n<ul>\n", $fstr ) ;
     $fstr = preg_replace( "/<\/ul>/i", "\n</ul>\n", $fstr ) ;
     $fstr = preg_replace( "/<\/?li[^>]*>/i", "\n", $fstr ) ;
     return $fstr ;
  }
?>

