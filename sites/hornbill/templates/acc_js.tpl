<!--Journal Slip Entry Screen : Swapna/28/04/2010 --!>
<div id = "WinAccJs" class = "centerbox" name = "WinAccJs" style = "top:1px; left:10px; width:920px; height:420px; " >
        <input type='hidden' id='OL_INSVW' value='10100' >
        <input type='hidden' id='OL_UPDVW' value='10100' >
        <input type='hidden' id='OL_VRFVW' value='10100' >
        <input type='hidden' id='OL_ATHVW' value='10100' >
        <input type='hidden' id='OL_EDSVW' value='10100' >

    <span  style="position:absolute;  left:20px; top:35px; " > Journal Slip Id</span>
    <input style="position:absolute;  left:135px; top:35px; width:150px;" type="text" id="EntId" />

    <span  style="position:absolute;  left:325px; top:35px;" > Date</span>
    <input style="position:absolute;  left:385px; top:35px; width:150px;" type="text" id="EntDt" />

    <span  style="position:absolute;  left:325px; top:65px;" > Client</span>
    <button onclick="Call_Search('WinAccJs','10102','', '', '0', 'EntCln');" style="position:absolute;  left:582px; width:20px; top:65px;height:20px">?</button>
    <input style="position:absolute;  left:385px; top:65px; width:200px;" type="text" id="EntClnNam" />
    <input style="position:absolute;  left:385px; top:65px; width:200px;" type="hidden" id="EntCln" />

    <input type= 'hidden' id='OL_SETEVT' value="EntCln#onChange#populateGroup('WinAccJs',2);">
    <span  style="position:absolute;  left:525px; top:35px;"> Remarks</span>
    <textarea style="position:absolute;  left:605px; top:35px; width:300px;height:50px;"  id="EntRmk" ></textarea>
    
    <div id="LstHoa" class="Grid" name="LstHoa" style="left:20px;top:105px; width:900px; height:290px;" >
	<input type='hidden' id='OL_INSVW' value='10101' >
	<input type='hidden' id='OL_UPDVW' value='10101' >
	<input type='hidden' id='OL_NOAG' value='-1' >
 </div>

</div>

