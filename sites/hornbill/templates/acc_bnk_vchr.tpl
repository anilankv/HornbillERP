<div id="WinBnkVchr" class="centerbox" style="left:0px;width:1030px;height:550px;" >
 <!--input type='hidden' id='OL_SETEVT' value="Payment Amount:a:LstBnkVchrPayDet#onchange#setTot();" /-->
 <input type='hidden' id='OL_INSVW' value='10121'>
 <!--input type='hidden' id='OL_UPDVW' value='30001'-->
 <input type='hidden' id='OL_EDSVW' value='10125'>
 <input type='hidden' id='OL_ATHVW' value='10124'>
 <!--input type='hidden' id='OL_DESVW' value='30001'-->

 <span  style="position:absolute;  left:20px; top:40px;" >Voucher ID</span>
  <input style="position:absolute;  left:870px; top:170px;width:105px;" type="text" id="EntVchrId" onChange="populateGroup('WinBnkVchr',3);" />
    <input style="position:absolute;  left:200px; top:40px;width:20px;height:23px" type="button" value="?" id="BtnCustSrch" onClick="Call_Search('WinBnkVchr','30439','EntVchrId', '0','0', 'EntVchrId');" />

<span  style="position:absolute;  left:240px; top:40px;" >DATE</span>
  <input style="position:absolute;  left:300px; top:40px;" type="text" id="EntDate" />

   <input type='hidden' id='OL_VRFVW' value='10123'>
<!--span  style="position:absolute;  left:710px; top:40px;" >Bill </span>
  <input style="position:absolute;  left:740px; top:40px;width:90px;" type="text" id="EntBillNo" />
<input style="position:absolute;  left:830px; top:40px;width:20px;height:23px" type="button" value="?" id="BtnBillSrch" onclick="Call_Search('WinBnkVchr','45012','EntBillNo' ,'0','0','EntBillNo');" /-->
<button  style="position:absolute;left:630px;width:150px;top:40px;" onclick="javascript:add_to_grid('20048','LstBnkVchrPayDet','WinBnkVchr');"/> Add Bill Details</button>
 <span  style="position:absolute;  left:430px; top:40px;" >CLIENT</span>
<input style="position:absolute;  left:820px; top:140px;width:125px;" type="hidden" id="EntClntId" />
    <input style="position:absolute;  left:610px; top:40px;width:20px;height:23px;" type="button" value="?" id="BtnCustSrch" onClick="Call_Search('WinBnkVchr','30785','EntClntId', '0', '0', 'EntClntId');" />
<input style="position:absolute;  left:490px; top:40px;width:120px;" type="text" id="EntAccCod" />
<input type='hidden' id='OL_SETEVT' value="EntAccCod#onchange#populateGroup('WinBnkVchr',2);">
<input type='hidden' id='OL_AFTVRF' value="bnk_vrf('WinBnkVchr',30782);">

 <!--input style="position:absolute;  left:680px; top:40px;width:20px;height:23px" type="button" value="?" id="BtnCustSrch" onClick="Call_Search('WinBnkVchr','126','EntCustNm', '0', '3#2', 'EntCstNm#EntCustNm');"/> --> 
<span  style="position:absolute;  left:510px; top:470px;" >UnAllot Amt</span>
  <input style="position:absolute;  left:580px; top:470px;" type="text" id="EntUnAmt" />

<div id="LstBnkVchrPayDet" class="Grid" name="LstBnkVchrPayDet" style="left:20px ; width:830px;top:100px; height:350px;">
   <input type='hidden' id='OL_UPDVW' value='10127'>
   <input type='hidden' id='OL_INSVW' value='10122'>
 
<!--input type='hidden' id='OL_SETEVT' value="Account Code:LstBnkVchrPayDet#onchange#populateGroup('WinBnkVchr',3);"-->  


<!--input type='hidden' id='OL_NOAG' value='-1'>
 <input type='hidden' id='OL_NRPG' value='1500'-->
</div>

<span  style="position:absolute;  left:50px; top:470px;" >REMARKS:</span>
 <textarea style="position:absolute;  left:120px; top:470px;height:50px;width:380px" id="EntRmk" ></textarea>
<span  style="position:absolute;  left:240px; top:70px;" >Trns Date</span>
  <input style="position:absolute;  left:300px; top:70px;" type="text" id="EntTrnsDt" />


<span  style="position:absolute;  left:20px; top:70px;" >As on Date</span>
  <input style="position:absolute;  left:100px; top:70px;" type="text" id="EntAoDate" />
<input style="position:absolute;  left:430px; top:70px;width:350px;" type="text" id="EntCnm" />
    <span  style="position:absolute;  left:800px; top:40px;" >Type</span>
    <select style="position:absolute;  left:860px; top:40px; width:150px;" type="text" id="CmbTyp" name="CmbTyp"> </select>
 <input type='hidden' id='CmbTyp:vno' value='30432' >
    <span  style="position:absolute;  left:800px; top:100px;" >Bank Type</span>

 <select style="position:absolute;  left:860px; top:100px; width:150px;" type="text" id="CmbBnkTyp" name="CmbBnkTyp"> </select>
 <input type='hidden' id='CmbBnkTyp:vno' value='30437' >
<input type='hidden' id='OL_SETEVT' value="CmbBnkTyp#onchange#populateGroup('WinBnkVchr',4);ColourforBank('WinBnkVchr','CmbBnkTyp');">

<!--span  style="position:absolute;  left:800px; top:130px;" >Ctrl No</span-->
  <input style="position:absolute;  left:95px; top:40px; width:105px;" type="text" id="EntCtrlNo" />
 <select style="position:absolute;  left:860px; top:140px; width:150px;" type="text" id="CmbUntTyp" name="CmbUntTyp"> </select>
 <input type='hidden' id='CmbUntTyp:vno' value='30462' >



<span  style="position:absolute;  left:800px; top:70px;" >Trans No</span>
  <input style="position:absolute;  left:860px; top:70px; width:150px;" type="text" id="EntNo" />

  <button  style="position:absolute;left:760px;width:60px;top:470px;" /> PRINT</button>

    
</div>
<script type = "text/javascript">
function setTot()
{
    document.getElementById("Entpamt").value = document.getElementById("Payment Amount:a:LstBnkVchrPayDet").innerHTML;
}
</script>


