<?php
  $rtn = array() ;
  $mod = isset( $_GET['mod']) ? $_GET['mod'] : ''  ;
  $cat = isset( $_GET['cat']) ? $_GET['cat'] : ''  ;
  $fpath = $root_path . "FGSTORE/STOCK/$cat/" ;
  $fN = '' ;
  if ( empty($mod)) {
     if (empty($_FILES{"fN"}{"name"})) return ;
     if (!is_dir($fpath)) mkdir($fpath, 0755, true);
     $aDt = getdate () ;
     $dir = $aDt['year'] . str_pad($aDt['mon'], 2, '0', STR_PAD_LEFT) . str_pad($aDt['mday'], 2, '0', STR_PAD_LEFT) ;
     $fpath  .=  $dir . '/' ;
     if (!is_dir($fpath)) mkdir($fpath, 0755, true);
     if(move_uploaded_file ( $_FILES{"fN"}{"tmp_name"}, $fpath.$_FILES{"fN"}{"name"} . '.' . $aDt[0] )) {
        $fN = $fpath . '/'  . $_FILES{"fN"}{"name"} . '.' . $aDt[0] ;
        $handle = fopen($fN, "r"); // $fn is uploaded file name with path
        $dt = array() ;
        $rs = $data->selectSet(" Begin ; ") ;
           //$data->selectSet("");
        $row = 0 ;
        while (!feof($handle) ) {
           $a = fgetcsv($handle, 1024, "\t");
           if ($a[0] == "" ) continue ;
           if (substr($a[0],0,4) == "CODE" ) continue ;
    //       $a[20] = substr($a[5],0,2) . '-' . substr($a[5],3,2) . '-20' . substr($a[5],6,2) ;
//code|codea|codeb|carno|carnoy|scdate|qty|flg|lock|codeo|codeao|notify|wono1|wono2|wono3|rem|oirno|srlno|carnop|cartonno|loc|scd
//CODE,CODEA,CODEB,CARNO,CARNOY,SCDATE,QTY,FLG,LOC,NO,WONO1,WONO2,WONO3,OIRNO,SRL_B,SRL_E,SRLNO,SCNUPD
 
           $sql = "INSERT INTO data.carton_daily( code,codea,codeb,carno,carnoy,scdate,qty,flg,loc,no,wono1,wono2,wono3,oirno,srl_b,srl_e,srlno,scnupd) VALUES ( '{$a[0]}','{$a[1]}','{$a[2]}','{$a[3]}','{$a[4]}','{$a[5]}','{$a[6]}','{$a[7]}','{$a[8]}','{$a[9]}','{$a[10]}','{$a[11]}','{$a[12]}','{$a[13]}','{$a[14]}','{$a[15]}','{$a[16]}','{$a[17]}') ;" ;
           $rs = $data->selectSet( $sql );
           $row += 1 ;
           $rtn[5] = $sql ;
        }
         $data->selectSet("update data.carton_daily set scd=(SELECT to_date(scdate, 'DD/MM/YY'));");
        $data->selectSet("update data.carton_daily set carton_no=round(carno::numeric)||carnoy;");
        $rs1 = $data->selectSet( "SELECT data.carton_update_daily();" ) ;
        $rs2 = $data->selectSet( "SELECT data.stock_bal_update_daily();");
        $rs1 = $data->selectSet( "Commit ;" ) ;
        fclose($handle ) ; 
        $rtn[0] = 0 ;
        $rtn[1] = $fN ;
        $rtn[2] = 1 ;
        $rtn[3] = 1 ;
        $rtn[4] = 1 ;
        echo "(" . json_encode($rtn) . ")" ;   
     }
     return ;
  }
?>

