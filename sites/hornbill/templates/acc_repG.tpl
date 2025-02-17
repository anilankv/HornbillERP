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
  <table border=1 style="position:absolute; left:20px; top:170px;width:800">
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
       <td style="text-align:right;" colspan="5">Opening balance on {fdt}</td>
       <td style="text-align:right;"><b>{ob}</b></td>
      </tr>
      <tr>
       <th>Date</th>
       <th>Particulars</th>
       <th>Type</th>
       <th>Ref. No.</th>
       <th>Debit</th>
       <th>Credit</th>
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
       <td style="text-align:right;" colspan="5">Closing balance on {tdt}</td>
       <td style="text-align:right;"><b>{cb}</b></td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
 </body>
</html>
