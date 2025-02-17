<?
   function Fill_Place_Combo ( $pLvl, $pCLvl = 0, $pId = '',  $pIStr = '', &$sLtxt  ){
      global $data, $template ;
//      if (!is_array($sLtxt)) $sLtxt = array() ; 
//      $aryIdx = 'sList' . ($pCLvl + 1) ;
      if ( $pCLvl == 0) $rs = $data->getSelect("SELECT cn.id,gn.nam from colc.nation cn,gen.nations gn WHERE cn.id=gn.id;") ;
      else $rs = $data->getSelect ( "SELECT * from colc.place_mstr where nid = '$pId';" ) ;
      $sLstI = ($pId == '') ? $pIStr : $pIStr . ".forValue('". $pId ."')" ;
//      $sLst =  $sLstI . "addOptionsTextValue('','') ;\n"   ;
//      $sLst .=  $sLstI . "addOptionsTextValue("   ;
//      $comma = "" ;
      $sLst =  $sLstI . ".addOptionsTextValue('',''"   ;
      while (! ($rs->EOF) ){
//         $sLst .= $comma . "'" .  $data->Get_Short_Sub_Name($rs->f['name']) . "','" . $rs->f['id'] . "'" ; 
         $sLst .=  ",'" . $rs->f['nam'] . "','" . $rs->f['id'] . "'" ; 
         $comma = "," ;
         if ( $pLvl > $pCLvl){
	    if( $rs->f['id'] != '' ) Fill_Place_Combo ( $pLvl, $pCLvl + 1, $rs->f['id'], $sLstI, $sLtxt ) ;
	 }
         if ( $pCLvl == 0 ) {
            $template->assign_block_vars( 'sList1' , array(
               "id" => $rs->f['id'],
               "nam" => $rs->f['nam'] )
            );
         }
         $rs->MoveNext();
      }
      $sLst .= ");\n\t" ;
      $sLtxt[$pCLvl] = ( isset($sLtxt[$pCLvl]))? $sLtxt[$pCLvl] . $sLst : $sLst ;
   }

   function Nation_Combo ( $pLvl, $wNam  ){
      global $template ;
      $sLtxt = array() ;
      Fill_Place_Combo ( $pLvl, 0 , '',  $wNam, $sLtxt ) ;
      for ( $cnt = 1 ; $cnt < $pLvl ; $cnt++ ){
         $aryIdx = 'sList' . ($cnt + 1) ;
         if ( isset($sLtxt[$cnt]) )$template->assign_vars(array( $aryIdx  => $sLtxt[$cnt] ) );
      }
   }
?>
