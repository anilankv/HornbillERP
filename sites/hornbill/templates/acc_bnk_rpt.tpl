<div id="WinBnkRpt" class="centerbox" style="left:0px;width:840px;height:550px;" >
 <!--input type='hidden' id='OL_SETEVT' value="Payment Amount:a:LstBnkRcptPayDet#onchange#setTot();" /-->
 <input type='hidden' id='OL_INSVW' value='10112'/>
 <!--input type='hidden' id='OL_UPDVW' value='30001'-->

 <input type='hidden' id='OL_EDSVW' value='10116'>
 <input type='hidden' id='OL_VRFVW' value='10114'>
 <input type='hidden' id='OL_ATHVW' value='10115'>
 <!--input type='hidden' id='OL_DESVW' value='30001'-->

 <span  style="position:absolute;  left:20px; top:40px;" >RECEIPT ID</span>
  <input style="position:absolute;  left:100px; top:40px;width:105px;" type="hidden" id="EntRcptId" onChange="populateGroup('WinBnkRpt',3);" />
    <input style="position:absolute;  left:200px; top:40px;width:20px;height:23px" type="button" value="?" id="BtnCustSrch" onClick="Call_Search('WinBnkRpt','30441','EntRcptId', '0','0', 'EntRcptId');" />

<span  style="position:absolute;  left:240px; top:40px;" >DATE</span>
  <input style="position:absolute;  left:310px; top:40px;" type="text" id="EntDate" />

<!--span  style="position:absolute;  left:710px; top:40px;" >Bill </span>
  <input style="position:absolute;  left:740px; top:40px;width:90px;" type="text" id="EntBillNo" />
<input style="position:absolute;  left:830px; top:40px;width:20px;height:23px" type="button" value="?" id="BtnBillSrch" onclick="Call_Search('WinBnkRpt','45012','EntBillNo' ,'0','0','EntBillNo');" /-->

<button  style="position:absolute;left:670px;width:140px;top:40px;" onclick="javascript:add_to_grid('30030','LstBnkRcptPayDet','WinBnkRpt');"/> Add Bill Details</button>
 <span  style="position:absolute;  left:450px; top:40px;" >CLIENT</span>
<input style="position:absolute;  left:820px; top:40px;width:100px;" type="hidden" id="EntClntId" onChange="populateGroup('WinBnkRpt','2');" />
    <input style="position:absolute;  left:620px; top:40px;width:20px;height:23px;" type="button" value="?" id="BtnCustSrch" onClick="Call_Search('WinBnkRpt','30784','EntClntId', '0', '0', 'EntClntId');" />
<!--input type='hidden' id='OL_SETEVT' value="EntClntId#onchange#populateGroup('WinBnkRpt',3);"-->
<input type='hidden' id='OL_SETEVT' value="EntAccCod#onchange#populateGroup('WinBnkRpt',3);">

<span  style="position:absolute;  left:20px; top:70px;" >As on Date</span>
  <input style="position:absolute;  left:100px; top:70px;" type="text" id="EntAoDate" />
<input style="position:absolute;  left:500px; top:70px;width:310px;" type="text" id="EntCnm" />

<div id="LstBnkRcptPayDet" class="Grid" name="LstPymntDtls" style="left:40px ; width:760px;top:100px; height:350px;">
<input type='hidden' id='OL_INSVW' value='10113'>
 <!--input type='hidden' id='OL_INSVW' value='10114'-->

  <input type='hidden' id='OL_UPDVW' value='10118'/>
   <!--input type='hidden' id='OL_INSVW' value='30002'>
   <input type='hidden' id='OL_UPDVW' value='30002'-->
   <input type='hidden' id='OL_NOAG' value='-1'>
 <input type='hidden' id='OL_NRPG' value='1500'>
</div>
  <input type='hidden' id='OL_SETEVT' value="EntAccCod#onchange#populateGroup('WinBnkRpt',3);"/>
<span  style="position:absolute;  left:20px; top:465px;" >REMARKS:</span>
 <textarea style="position:absolute;  left:100px; top:460px;height:55px;width:380px" id="EntRmk" ></textarea>
<button style="position:absolute; left:510px;top:490px;height:30px;widthi:150px" onClick="Call_Service('WinBnkRpt','30061',null,null,null,null,null,'ADD',1);">Bank Document</button>
<button style="position:absolute; left:644px;top:490px;height:30px;width:150px" onClick="Call_Service('WinBnkRpt','30071',null,null,null,null,null,'SHW',1);">Bank Clearance</button>
<input style="position:absolute;  left:500px; top:40px;width:120px;" type="text" id="EntAccCod" />
 <select style="position:absolute;  left:680px; top:430px; width:150px;" type="text" id="CmbUntTyp" name="CmbUntTyp"> </select>
 <input type='hidden' id='CmbUntTyp:vno' value='30462' >

<span  style="position:absolute;  left:510px; top:460px;" >UnAlloted Amount</span>
  <input style="position:absolute;  left:625px; top:460px;" type="text" id="EntUnAmt" />

  <span  style="position:absolute;  left:240px; top:70px;" >Bank Type</span>

 <select style="position:absolute;  left:310px; top:70px; width:150px;" type="text" id="CmbBnkTyp" name="CmbBnkTyp"> </select>
 <input type='hidden' id='CmbBnkTyp:vno' value='30437' >
<input type='hidden' id='OL_SETEVT' value="CmbBnkTyp#onchange#populateGroup('WinBnkRpt',4);ColourforBank('WinBnkRpt','CmbBnkTyp');">
<input type='hidden' id='OL_AFTVRF' value="bnk_vrf('WinBnkRpt',30783);">

  <!--button  style="position:absolute;left:560px;width:60px;top:500px;" /> PRINT</button-->

   <!--span  style="position:absolute;  left:800px; top:130px;" >Ctrl No</span-->
  <input style="position:absolute;  left:100px; top:40px; width:100px;" type="text" id="EntCtrlNo" />
 
</div>
<script type = "text/javascript">
function setTot()
{
    document.getElementById("Entpamt").value = document.getElementById("Payment Amount:a:LstBnkRcptPayDet").innerHTML;
}
</script>


