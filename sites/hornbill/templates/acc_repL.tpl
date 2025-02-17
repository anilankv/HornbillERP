<html>
 <head>
  <link href="./css/app.min.1.css" type="text/css" rel="stylesheet">
<style>
.pagination-centered{text-align:center;}
body {
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  font-size: 13px;
  line-height: 1.42857143;
  color: #5e5e5e;
  background-color: #E9EBEE;
}
 </style>  
 </head>
 <body>
 <body>
  <div class="centerbox-container pagination-centered" style="margin-top:-75px;">
   <div class="col-md-offset-4 col-md-4" style="padding:0">
     <h3><b>{nam}</b></h3>
     <h4><b>{adr1}, {adr2}, {adr3}</b></h4>
     <h4></b>({coa} - {ccod}){rnam}</b></h4>
     <h4><b>({fdt} to {tdt})</b></h4>
   </div>
  </div>
  <table class="table table-hover table-striped">
  <thead>
   <tr>
    <th colspan=3>FROM DATE :{fdt}</th>
    <th colspan=3>To DATE :{tdt}</th>
   </tr>
  </thead>
     <thead>
      <tr class="active">
       <td><b>DATE</b></td>
       <td><b>PARTICULARS</b></td>
       <td><b>TYPE</b></td>
       <td><b>REF. NO.</b></td>
       <td><b>DEBIT</b></td>
       <td><b>CREDIT</b></td>
      </tr>
     </thead> 
     <tbody>
      <tr>
       <td>&nbsp;</td>
       <td><b>Opening balance on {fdt}<b></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td><b>{ob}</b></td>
       <td>&nbsp;</td>
      </tr>
      <!-- BEGIN stmt -->
      <tr class="{stmt.typ}">
       <td>{stmt.adt}</td>
       <td>{stmt.nrtn}</td>
       <td>{stmt.tref}</td>
       <td>{stmt.rid}</td>
       <td>{stmt.debit}</td>
       <td>{stmt.credit}</td>
      </tr>
      <!-- END stmt -->
      <tr>
       <td>&nbsp;</td>
       <td><b>Closing balance on {tdt}</b></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td><b>{cb}</b></td>
      </tr>
     </tbody>
  </table>
 </body>
</html>
