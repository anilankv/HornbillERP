<html>
 <head>
  <style TYPE="text/css">
  <!--
   @page { margin: 0.79in }
   table.outr { border:1px solid #000000; }
   td { border-bottom: 1px solid #999999; border-right: 1px solid #000000; text-align:left; padding:0.01in; font-size:11pt}
   tr td:last-child { border-right: 0; }
   tr:last-child td { border-bottom: 0; }
   tr.ttl td, td.ttl {text-align:center;font-weight:bold} 
   td.sd { vertical-align:top;border:1px solid #000}
   .C .l, .CG .l{padding-left:0.4in;}
   .CC .l{padding-left:0.8in;}
   .G {font-weight:bold;font-size:13pt} 
   .CG {font-weight:bold;font-size:11pt;font-style:oblique;} 
   td.v {text-align:right;padding-right: 0.04in; }
   span {text-align:center:padding:0;border:0;margin:0;width:100%;}
   span.sub {font-weight:bold;font-size:11pt;font-style:oblique;}
   span.main {font-weight:bold;font-size:18pt;}
   span.rnm {font-weight:bold;font-size:16pt;}
  -->
  </style>
 </head>
 <body>
  <table class="outr">
   <tr class="ttl" >
    <td colspan=6>
     <span class='main'>{nam}</span><br/>
     <span class='sub'>{adr1}, {adr2}, {adr3}</span><br/>
     <span class='rnm'>{rnam}</span><br/>
     <span class='sub'>{fdt} to {tdt}</span>
    </td>
   </tr>
   <tr>
    <td colspan=2 class="sd">
     <table>
      <!-- BEGIN l -->
      <tr>
       <td class="l">{l.unm}</td>
       <td class="v">{l.amt}</td>
      </tr>
      <!-- END l -->
     </table>
    </td>
    <td>
    </td>
    <td colspan=2 class="sd">
     <table>
      <!-- BEGIN r -->
      <tr>
       <td class="l">{r.unm}</td>
       <td class="v">{r.amt}</td>
      </tr>
      <!-- END r -->
     </table>
    </td>
   </tr>
   <tr class="G">
    <td class="v">Total</td>
    <td class="v">{lft}</td>
    <td class="v"></td>
    <td class="v">Total</td>
    <td class="v">{rht}</td>
   </tr>
  </table>
 </body>
</html>
