<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
 <head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="KELTRON KSEDC Keralam">
  <title><? echo $conf['appName'] ?></title>
  <!-- Vendor CSS -->
  <link href="/css/hornbill.css" rel="stylesheet">
  <link href="/css/fullcalendar.min.css" rel="stylesheet">
  <link href="/css/animate.min.css" rel="stylesheet">
  <link href="/css/sweetalert-override.min.css" rel="stylesheet">
  <link href="/css/material-design-iconic-font.min.css" rel="stylesheet">
  <link href="/css/socicon.min.css" rel="stylesheet">
  <!-- CSS -->
  <link href="/css/app.min.1.css" rel="stylesheet">
  <link href="/css/app.min.2.css" rel="stylesheet">
  <script src="/js/jquery.min.js"></script>
  <script src="/js/jquery.nicescroll.min.js"></script>
  <script src="/js/waves.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
<!--
  <script src="/js/jquery.flot.js"></script>
  <script src="/js/jquery.flot.resize.js"></script>
  <script src="/js/curvedLines.js"></script>
  <script src="/js/jquery.easypiechart.min.js"></script>
  <script src="/js/jquery.sparkline.min.js"></script>
  <script src="/js/moment.min.js"></script>
  <script src="/js/fullcalendar.min.js"></script>
  <script src="/js/jquery.simpleWeather.min.js"></script>
  <script src="/js/jquery.nicescroll.min.js"></script>
  <script src="/js/bootstrap-growl.min.js"></script>
  <script src="/js/sweetalert.min.js"></script>
  <script src="/js/curved-line-chart.js"></script>
  <script src="/js/line-chart.js"></script>
  <script src="/js/charts.js"></script>
  <script src="/js/demo.js"></script>
  <script src="/js/common.js"></script>
-->
  <script src="/js/functions.js"></script>
  <script src="/ol/osslib.js" type="text/javascript" ></script>
 </head>
 <body <? echo "onload=callSrv('$srv_g','$key_g','$mod_g','$m_g','$login')" ?> >
  <header id="top_g"></header>
  <section id="main">
   <aside id="lft_g"></aside>
   <aside id="rgt_g"></aside>
   <section id="bdy_g">
    <div id='initDiv'>
     <img  src="img/wait.gif" >
    </div>
   </section>
  </section>
  <footer id="bot_g"></footer>
  <div id="fde_g" class=" fade" style="display: none;"></div>
  <div id="wrn_g"></div>
  <input id="switch_g" type=hidden  value="<? echo $msg_g ?>" />
 </body>
</html>
