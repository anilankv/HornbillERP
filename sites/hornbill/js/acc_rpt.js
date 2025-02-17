function Call_Dis()
{
        mod     = getElmntById("CmbMod").value;
        bnk     = getElmntById("EntAcc");
	des     = getElmntById("EntDes");

if ( mod == 2 || mod == 3 )	// CHEQUE OR DD
 {
        bnk.disabled = true;
	des.disabled = false;

 }
else if (mod == 4)	 // BANK TRANSFER
{
        bnk.disabled = false;
        des.disabled = true;

}
else
{
        bnk.disabled = true;
        des.disabled = true;

}
}
