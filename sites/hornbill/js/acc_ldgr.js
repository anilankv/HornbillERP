function report_load(){
   var win = winA_g["WinLedgr"] ;
   var fDt = getWidVal(win, win.wA["EntFromDate"]) ;
   var tDt = getWidVal(win, win.wA["EntToDate"]) ;
   var rId = getWidVal(win, win.wA["CmbRpt"]) ;
   var CoA = getWidVal(win, win.wA["CmbCoa"]) ;
   var Acn = getWidVal(win, win.wA["EntAcc"]) ;
   var Unt = getWidVal(win, win.wA["CmbUnt"]) ;
   var Exp =getWidVal(win, win.wA["ChkExp"]) ;
   if(validate(rId))
    {
      var newWindow =  window.open("index.php?f=3&srv=337&fdt="+fDt+"&tdt="+tDt+"&rid="+rId+"&coa="+CoA+"&acn="+Acn+"&unt="+Unt+"&exp="+Exp,'_blank');
    }
}



function display_coa(){
   var win = winA_g["WinLedgr"] ;
   var rId = getWidVal(win, win.wA["CmbRpt"]) ;
   if(rId==5 || rId == 6){
       tglWidShw(win.wA["CmbCoa"],true);
       tglWidShw(win.wA["EntAcc"],false);
       win.wA["EntAcc"].rV = "NULL";
   }
   else if(rId==7){
       tglWidShw(win.wA["CmbCoa"],true);
       tglWidShw(win.wA["EntAcc"],true);
       win.wA["EntAcc"].value = "";
   }
   else {
       tglWidShw(win.wA["EntAcc"],false);
       tglWidShw(win.wA["CmbCoa"],false);
       win.wA["EntAcc"].rV = "NULL";
       win.wA["EntAcc"].value = "";
   }
}


