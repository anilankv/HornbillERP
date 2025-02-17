<div id = "WinAccPay" class = "centerbox" name = "WinAccPay" style = "top:1px; left:10px; width:790px; height:420px; " >
        <input type='hidden' id='OL_INSVW' value='10201' >
        <input type='hidden' id='OL_UPDVW' value='10201' >

    <span  style="position:absolute;  left:10px; top:35px;" > Voucher ID</span>
    <input style="position:absolute;  left:150px; top:35px; width:125px;" type="text" id="EntId" />

   <span  style="position:absolute;  left:10px; top:65px;" > Date</span>
   <input style="position:absolute;  left:150px; top:65px; width:125px;" type="text" id="EntDt" />

   <span  style="position:absolute;  left:10px; top:110px;">Client Id</span>
   <input style="position:absolute;  left:150px; top:110px; width:145px;" type="text" id="EntCln" />
   <input style="position:absolute;  left:290px; top:110px;width:20px;" type="button" value="?" id="BtnSrch" onClick="Call_Search('WinAccPay','10057','', '', '0', 'EntCln');" />
   <input type= 'hidden' id='OL_SETEVT' value="EntCln#onChange#populateGroup('WinAccPay',3);">

    <span  style="position:absolute;  left:10px; top:135px;">Client Name</span>
    <input style="position:absolute;  left:150px; top:135px; width:175px;" type="text" id="EntNam" >

    <span  style="position:absolute;  left:10px; top:165px;">Address</span>
    <input style="position:absolute;  left:150px; top:165px; width:175px;" type="text" id="EntAdr" >

    <span  style="position:absolute;  left:10px; top:195px;">Phone No</span>
    <input style="position:absolute;  left:150px; top:195px; width:175px;" type="text" id="EntPhn"/>

    <span  style="position:absolute;  left:10px; top:235px;"> Mode</span>
    <select style="position:absolute;  left:150px; top:235px; width:175px;" type="text" id="CmbMod"></select>
    <input type='hidden' id='CmbMod:vno' value='10201'>
    <input type='hidden' id='OL_SETEVT' value="CmbMod#onchange#Onpaymodchange();">
    <span  style="position:absolute;  left:10px; top:265px;"> Bank Account</span>
    <input style="position:absolute;  left:150px; top:265px; width:175px;" type="text" id="EntAcc" />
    <input style="position:absolute;  left:320px; top:267px;width:20px;" type="button" value="?" id="BtnSrch" onClick="Call_Search('WinAccPay','10207','', '', '3#1#0', 'EntAcc#EntBAdr#EntBNm');" />

    <span  style="position:absolute;  left:10px; top:290px;"> Bank Name</span>
    <input style="position:absolute;  left:150px; top:290px; width:175px;" type="text" id="EntBNm" />

    <span  style="position:absolute;  left:10px; top:315px;"> Address</span>
    <input style="position:absolute;  left:150px; top:315px; width:175px;" type="text" id="EntBAdr" />

    <span  style="position:absolute;  left:10px; top:345px;"> DD Commission</span>
    <input style="position:absolute;  left:150px; top:345px; width:175px;" type="text" id="EntDCm" />

    <span  style="position:absolute;  left:10px; top:370px;"> Instrument Description</span>
    <input style="position:absolute;  left:150px; top:370px; width:175px;" type="text" id="EntIDes" />
    <!--span  style="position:absolute;  left:10px; top:325px;">Debit Head </span-->
    <input style="position:absolute;  left:150px; top:320px; width:175px;" type="hidden" id="EntDHd"></select>
    <!--input style="position:absolute;  left:290px; top:95px;width:20px;" type="button" value="?" id="BtnSrch" onClick="Call_Search('WinPay','','EntMid', '0', '0#1#2#3#4', 'EntMid#EntNam#EntAdr#EntEid');" /-->
    <input type= 'hidden' id='OL_SETEVT' value="Debit Head:LstHd#onChange#populateGroup('WinAccPay',2);">
 <div id="LstHd" class="Grid" name="LstHd" style="left:340px;top:30px; width:460px; height:360px;" >
	<input type='hidden' id='OL_INSVW' value='10202' >
	<input type='hidden' id='OL_UPDVW' value='10202' >
 </div>


</div>

