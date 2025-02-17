<head>
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
<td colspan=6 valign=top>
<table border=0 cellpadding=3  style="width:790;">

   <!-- BEGIN sd -->
   <tr class="ttl">
    <td>Particulars</td>
    <td>Debit</td>
    <td>Credit</td>
   </tr>
   <!-- BEGIN rw -->
   <tr class="{sd.rw.tp}" >
    <td class="v">{sd.rw.nm}</td>
    <td class="v">{sd.rw.db}</td>
    <td class="v">{sd.rw.cb}</td>
   </tr>
<!-- END rw -->
<!-- END sd -->
   <tr>
   <td><b>Total</b></td>
   <td class="v"><b>{dtl}</b></td>
   <td class="v"><b>{ctl}</b></td>
   </tr>
</table>
</td>
</table>
</body>
