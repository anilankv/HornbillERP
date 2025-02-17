<div id = "WinBnkRecn" class = "centerbox" name = "WinBnkRecn" style = "top:1px; left:10px; width:825px; height:370px; " >
	<input type='hidden' id='OL_UPDVW' value='10501' >
	<input type='hidden' id='OL_INSVW' value='10501' >

<span  style="position:absolute;  left:10px; top:30px;" > RECONCILIATION ID</span>
    <input style="position:absolute;  left:220px; top:30px; width:125px;" type="text" id="EntId" />

	<span  style="position:absolute;  left:10px; top:70px;" > BANK ACCOUNT</span>
    <input style="position:absolute;  left:220px; top:70px; width:125px;" type="text" id="EntAcc" />
	<input style="position:absolute;  left:350px; top:70px;width:20px;" type="button" value="?" id="BtnSrch" onClick="Call_Search('WinBnkRecn','10058','EntAcc', '0', '0', 'EntAcc');" />
        <span  style="position:absolute;  left:450px; top:70px;" > LAST RECONCILED DATE </span>
    <input style="position:absolute;  left:640px; top:70px; width:125px;" type="text" id="EntLstDte" />

<span  style="position:absolute;  left:10px; top:110px;" > FROM DATE</span>
    <input style="position:absolute;  left:220px; top:110px; width:125px;" type="text" id="EntFrmDte" />
        <span  style="position:absolute;  left:450px; top:110px;" > TO DATE </span>
    <input style="position:absolute;  left:640px; top:110px; width:125px;" type="text" id="EntToDte" />


<div id= "LstBnkRec" class = "Grid"  style="position:absolute;  left:10px; top:150px;" > 
	<input type= 'hidden' id='OL_SETEVT' value="1:LstBnkRec#onchange#populateGroup('WinBnkRecn',2);">
	 <input type= 'hidden' id='OL_SETEVT' value="0:LstBnkRec#onchange#Call_Dis();">
        <input type='hidden' id='OL_UPDVW' value='10503' >
        <input type='hidden' id='OL_INSVW' value='10503' >


</div>

</div>
