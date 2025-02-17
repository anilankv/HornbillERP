<div id="WinAccMtrlRqst" class="centerbox" style="left:10px; top:10px;width:830px; height:520px ; " >
     		<input type='hidden' id='OL_INSVW' value='11176'/>
                <input type='hidden' id='OL_UPDVW' value='11213'/>
         	<input type='hidden' id='OL_VRFVW' value='11178'/>
                 <input type='hidden' id='OL_EDSVW' value='11179'>
                 <!--input type='hidden' id='OL_VRFVW' value='11195'/>
                 <input type='hidden' id='OL_ATHVW' value='11196'>
                 <input type='hidden' id='OL_EDSVW' value='11197'>
                 <input type='hidden' id='OL_ATHVW' value='11198'>
                <input type='hidden' id='OL_UPDVW' value='11199'/>
                <input type='hidden' id='OL_VRFVW' value='11205'/>
                 <input type='hidden' id='OL_ATHVW' value='11206'>
                 <input type='hidden' id='OL_EDSVW' value='11207'-->


	<div id="LstMtrlRqst" class="Grid" name="LstMtrlRqst" style="left:10px ; width:800px;top:120px; height:290px;">
<input type='hidden' id='OL_SETEVT' value="0:LstMtrlRqst#onchange#populateGroup('WinAccMtrlRqst',2);">

         	 <input type='hidden' id='OL_INSVW' value='11177'/>
		 <input type='hidden' id='OL_UPDVW' value='11214'/>
		 <input type='hidden' id='OL_DELVW' value='11215'/>
	</div>
  <span  style="position:absolute;  left:10px; top:455px;" >Unit</span>
       <select style="position:absolute;  left:100px; top:455px; width:100px;" type="text" id="CmbUnt" name="CmbUnt"> </select>
    <input type='hidden' id='CmbUnt:vno' value='30705'/>
 
  <span  style="position:absolute;  left:10px; top:30px;" >PR No</span>
    <input style="position:absolute;  left:130px; top:30px;width:100px;"  type="text" id="EntMtrlrqNo" />
  <span  style="position:absolute;  left:500px; top:30px;" >Date</span>
    <input style="position:absolute;  left:580px; top:30px;width:100px;"  type="text" id="EntRqDate"/>
  <span  style="position:absolute;  left:250px; top:30px;" >Intenting Department</span>
    <select style="position:absolute;  left:380px; top:30px; width:100px;" type="text" id="CmbInDprt" name="CmbInDprt"> </select>
    <input type='hidden' id='CmbInDprt:vno' value='11177'/>
  <span  style="position:absolute;  left:10px; top:60px;" >Budget Head</span>
    <select style="position:absolute;  left:130px; top:60px; width:100px;" type="text" id="CmbBudHd" name="CmbBudHd"> </select>
    <input type='hidden' id='CmbBudHd:vno' value='30704'/>
    <!--input style="position:absolute;  left:130px; top:60px;width:100px;"  type="text" id="CmbBudHd"/-->

  <span  style="position:absolute;  left:250px; top:60px;" >Intentor's Reference</span>
    <input style="position:absolute;  left:380px; top:60px;width:100px;"  type="text" id="EntInRef"/>
  <span  style="position:absolute;  left:500px; top:60px;" >Intentor Ref Dt</span>
    <input style="position:absolute;  left:580px; top:60px;width:100px;"  type="text" id="EntInRdt"/>
  <span  style="position:absolute;  left:10px; top:90px;" >Delivery Date</span>
    <input style="position:absolute;  left:130px; top:90px;width:100px;"  type="text" id="EntDDt"/>
  <span  style="position:absolute;  left:250px; top:90px;" >CPD Reference</span>
    <input style="position:absolute;  left:380px; top:90px;width:100px;"  type="text" id="EntCpdRef"/>
  <span  style="position:absolute;  left:500px; top:90px;" >CPD Ref Dt</span>
    <input style="position:absolute;  left:580px; top:90px;width:100px;"  type="text" id="EntInCpddt"/>
  <span  style="position:absolute;  left:10px; top:430px;" >Justification</span>
    <textarea id="txtJstPur" name="txtJstPur"  style="position:absolute;  left:100px; top:420px; width:240px;height:25px" ></textarea>
  <span  style="position:absolute;  left:380px; top:430px;" >Remarks</span>
    <textarea id="txtRmk" name="txtRmk"  style="position:absolute;  left:440px; top:420px; width:240px; height:50px;" ></textarea>

 <input style="position:absolute;  left:580px; top:90px;width:100px;"  type="hidden" id="EntUsrDpt"/>
<button  style="position:absolute;  left:690px; top:430px;width:130px;height:30px;" onClick="pr_req()">Print</button>
 <input style="position:absolute;  left:580px; top:90px;width:100px;"  type="hidden" id="EntUsrDpt"/>
<select style="position:absolute;  left:210px; top:485px; width:330px" type="text" id="CmbPrSts" name="CmbPrSts"> </select>
    <input type='hidden' id='CmbPrSts:vno' value='11174'/>

</div>
