<html>
 <head>
  <link href="./css/tablestyle.css" type="text/css" rel="stylesheet">
 </head>
 <body>
  <span style="position:absolute;left:30;top:10;width:800;text-align:center"><h2>{nam}</h2></span>
  <span style="position:absolute;left:30;top:30;width:800;text-align:center"><h4>{adr1}</h4></span>
  <span style="position:absolute;left:30;top:45;width:800;text-align:center"><h4>{adr2}</h4></span>
  <span style="position:absolute;left:30;top:60;width:800;text-align:center"><h4>{adr3}</h4></span>
  <span style="position:absolute;left:30;top:90;width:800;text-align:center"><h3>{rnam}</h3></span>
  <span style="position:absolute;left:30;top:130;width:800;text-align:center">{fdt} to {tdt}</span>
  <table border=1 class="table table-hover" style="position:absolute; left:20px; top:170px;width:800;border-collapse:collapse;">
   <tr>
    <td colspan=3>From Date :{fdt}</td>
    <td colspan=3>To Date :{tdt}</td>
   </tr>
   <tr>
    <th colspan=6>{lhd}</th>
   </tr>
   <tr>
    <td colspan=6 valign=top>
     <table border=0 cellpadding=3  style="width:790;text-align:center;">
      <tr>
       <th>Date</th>
       <th>Particulars</th>
       <th>Type</th>
       <th>Ref. No.</th>
       <th>Debit</th>
       <th>Credit</th>
      </tr>
      <tr>
       <td style="text-align:right;">&nbsp;</td>
       <td style="text-align:left;"><b>Opening balance on {fdt}<b></td>
       <td style="text-align:left;">&nbsp;</td>
       <td style="text-align:left;">&nbsp;</td>
       <td style="text-align:right;"><b>{ob}</b></td>
       <td style="text-align:left;">&nbsp;</td>
      </tr>
      <!-- BEGIN stmt -->
      <tr class="{stmt.typ}">
       <td style="text-align:center;">{stmt.adt}</td>
       <td style="text-align:left;">{stmt.nrtn}</td>
       <td style="text-align:center;">{stmt.tref}</td>
       <td style="text-align:center;">{stmt.rid}</td>
       <td style="text-align:right;">{stmt.debit}</td>
       <td style="text-align:right;">{stmt.credit}</td>
      </tr>
      <!-- END stmt -->
      <tr>
       <td style="text-align:right;">&nbsp;</td>
       <td style="text-align:left;"><b>Closing balance on {tdt}</b></td>
       <td style="text-align:left;">&nbsp;</td>
       <td style="text-align:left;">&nbsp;</td>
       <td style="text-align:right;"><b>{cb}</b></td>
       <td style="text-align:left;">&nbsp;</td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
 </body>
</html>
