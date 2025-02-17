<div id = "WinAccRpt" class = "centerbox" name = "WinAccRpt" style = "top:1px; left:10px; width:775px; height:470px; " >
        <input type='hidden' id='OL_UPDVW' value='10053' >
        <input type='hidden' id='OL_INSVW' value='10053' >

    <span  style="position:absolute;  left:10px; top:30px;" > Receipt ID</span>
    <input style="position:absolute;  left:150px; top:30px; width:125px;" type="text" id="EntId" />

   <span  style="position:absolute;  left:10px; top:65px;" > Date</span>
   <input style="position:absolute;  left:150px; top:65px; width:125px;" type="text" id="EntDt" />
    <input style="position:absolute;  left:150px; top:65px; width:175px;" type="hidden" id="EntHdt"/>
   <input type='hidden' id='OL_SETEVT' value="EntDt#onChange#cpValTo('WinAccRpt','EntDt','EntHdt');">

   <span  style="position:absolute;  left:10px; top:100px;">Client Id</span>
   <input style="position:absolute;  left:150px; top:100px; width:145px;" type="text" id="EntCln" />
   <input style="position:absolute;  left:290px; top:100px;width:20px;" type="button" value="?" id="BtnSrch" onClick="Call_Search('WinAccRpt','10057','EntCln', '0', '0#2#3#4', 'EntCln#EntNam#EntAdr#EntPhn');" />
   <!--input type= 'hidden' id='OL_SETEVT' value="EntPhn#onChange#populateGroup('WinAccRpt',2);"-->

    <span  style="position:absolute;  left:10px; top:135px;">Client Name</span>
    <input style="position:absolute;  left:150px; top:135px; width:175px;" type="text" id="EntNam" >

    <span  style="position:absolute;  left:10px; top:170px;">Address</span>
    <input style="position:absolute;  left:150px; top:170px; width:175px;" type="text" id="EntAdr" >

    <span  style="position:absolute;  left:10px; top:205px;">Phone No</span>
    <input style="position:absolute;  left:150px; top:205px; width:175px;" type="text" id="EntPhn"/>

    <span  style="position:absolute;  left:10px; top:240px;"> Mode</span>
    <select style="position:absolute;  left:150px; top:240px; width:175px;" type="text" id="CmbMod" onChange="Call_Dis()"></select>
    <input type='hidden' id='CmbMod:vno' value='10201'>

    <span  style="position:absolute;  left:10px; top:280px;"> Bank Account No</span>
    <input style="position:absolute;  left:150px; top:280px; width:145px;" type="text" id="EntAcc" />
    <input style="position:absolute;  left:290px; top:280px;width:20px;" type="button" value="?" id="BtnSrch" onClick="Call_Search('WinAccRpt','10058','EntAcc', '0', '0#2#3', 'EntAcc#EntBnkNam#EntBnkAdr');" />

   <span  style="position:absolute;  left:10px; top:320px;"> Bank Name</span>
   <input style="position:absolute;  left:150px; top:320px; width:175px;" type="text" id="EntBnkNam" />

   <span  style="position:absolute;  left:10px; top:360px;"> Bank Address</span>
   <input style="position:absolute;  left:150px; top:360px; width:175px;" type="text" id="EntBnkAdr" />

    <span  style="position:absolute;  left:10px; top:400px;"> Instrument Description</span>
    <input style="position:absolute;  left:150px; top:400px; width:175px;" type="text" id="EntDes" />

    <span  style="position:absolute;  left:10px; top:440px;"> Remarks</span>
    <input style="position:absolute;  left:150px; top:440px; width:175px;" type="text" id="EntRmk" />

     <div id="LstRptHd" class="Grid" name="LstRptHd" style="left:340px;top:30px; width:445px; height:420px;" >
	<input type= 'hidden' id='OL_SETEVT' value="Credit Head:LstRptHd#onChange#populateGroup('WinAccRpt',3);">
	<input type='hidden' id='OL_INSVW' value='10054' >
	<input type='hidden' id='OL_UPDVW' value='10054' >
      </div>


</div>

