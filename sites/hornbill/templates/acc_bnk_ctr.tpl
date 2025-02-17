<div id = "WinAccBnkCtr" class = "centerbox" name = "WinAccBnkCtr" style = "top:5px;left:10px;width:630px;height:350px; ">
        <input type='hidden' id='OL_UPDVW' value='10055' >
        <input type='hidden' id='OL_INSVW' value='10055' >

    <span  style="position:absolute;  left:10px; top:30px;" > Contra Id</span>
    <input style="position:absolute;  left:90px; top:30px; width:125px;" type="text" id="EntId" />

   <span  style="position:absolute;  left:10px; top:70px;" > Type</span>
   <select style="position:absolute;  left:90px; top:70px; width:175px;" type="text" id="CmbTyp" ></select>
   <input type='hidden' id='CmbTyp:vno' value='10060'>
   <input type= 'hidden' id='OL_SETEVT' value="CmbTyp#onChange#populateGroup('WinAccBnkCtr',1);">

   <span  style="position:absolute;  left:340px; top:70px;">Date</span>
   <input style="position:absolute;  left:460px; top:70px; width:145px;" type="text" id="EntDte" />

    <span  style="position:absolute;  left:10px; top:110px;">Debit Head</span>
    <select style="position:absolute;  left:90px; top:110px; width:175px;" type="text" id="CmbDbt" ></select>
   <input type='hidden' id='CmbDbt:vno' value='10053'>

    <span  style="position:absolute;  left:340px; top:110px;">Debit Account No</span>
    <input style="position:absolute;  left:460px; top:110px; width:175px;" type="text" id="EntDbtAcc" >
    <input style="position:absolute;  left:620px; top:110px;width:20px;" type="button" value="?" id="BtnSrch" onClick="Call_Search('WinAccBnkCtr','10058','EntDbtAcc', '0', '0#2#3', 'EntDbtAcc#EntDbtNam#EntDbtAdr');" />

    <span  style="position:absolute;  left:10px; top:150px;">Debit Name</span>
    <input style="position:absolute;  left:90px; top:150px; width:175px;" type="text" id="EntDbtNam" >

    <span  style="position:absolute;  left:340px; top:150px;">Debit Address</span>
    <input style="position:absolute;  left:460px; top:150px; width:175px;" type="text" id="EntDbtAdr" >

    <span  style="position:absolute;  left:10px; top:190px;">Credit Head</span>
    <select style="position:absolute;  left:90px; top:190px; width:175px;" type="text" id="CmbCdt"/></select>
    <input type='hidden' id='CmbCdt:vno' value='10053'>

    <span  style="position:absolute;  left:340px; top:190px;">Credit Account No</span>
    <input style="position:absolute;  left:460px; top:190px; width:175px;" type="text" id="EntCdtAcc">
    <input style="position:absolute;  left:620px; top:190px;width:20px;" type="button" value="?" id="BtnSrch" onClick="Call_Search('WinAccBnkCtr','10058','EntCdtAcc', '0', '0#2#3', 'EntCdtAcc#EntCdtNam#EntCdtAdr');" />


    <span  style="position:absolute;  left:10px; top:230px;">Credit Name</span>
    <input style="position:absolute;  left:90px; top:230px; width:175px;" type="text" id="EntCdtNam" >

    <span  style="position:absolute;  left:340px; top:230px;">Credit Address</span>
    <input style="position:absolute;  left:460px; top:230px; width:175px;" type="text" id="EntCdtAdr" >

    <span  style="position:absolute;  left:10px; top:270px;"> Amount</span>
    <input style="position:absolute;  left:90px; top:270px; width:175px;" type="text" id="EntAmt" />

    <span  style="position:absolute;  left:340px; top:270px;"> Remarks</span>
    <input style="position:absolute;  left:460px; top:270px; width:175px;" type="text" id="EntRmk" />

</div>

