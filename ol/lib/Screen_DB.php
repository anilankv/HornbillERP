<?php
$sid = isset( $_GET['srvno']) ? $_GET['srvno'] : ''  ;
          $qry =$data->getSelect("SELECT * from sw.srv where id='$sid'", $rA, 'S', __LINE__ );
          $header = $qry->f['snm'];
	  $folder =$root_path . 'screens/'.$qry->f['snm'].'/';
	  $sc_fldr = $root_path . 'screens/';
	  $snm = $qry->f['snm'];
	  `mkdir $folder`;  
          $handle=fopen($folder.$header.'.sql','w+');
//-- screen  start ----
          $del_txt = "delete from sw.screen where snm = '".$header."';\n\n";
          $qry =$data->getSelect("SELECT * from sw.screen where snm = '".$header."'", $rA, 'S', __LINE__ );
          if ($qry->f['eid']=='NULL' || $qry->f['eid']=='')
            $qry->f['eid']='NULL';
          if ($qry->f['mod']=='NULL' || $qry->f['mod']=='')
            $qry->f['mod']='NULL';
          fwrite($handle,"insert into sw.screen(snm,kwnm,ttl,pcss,typ,tmf,eid,nam,mod,icn,top,lft,rgt,bdy ,bot) values ('". $qry->f['snm']."' , '".$qry->f['kwnm']."' , '".$qry->f['ttl']."' , '".$qry->f['pcss']."' , '".$qry->f['typ']."','". $qry->f['tmf']."' ,".$qry->f['eid']." ,'". $qry->f['nam']."' ,".$qry->f['mod']." ,'". $qry->f['icn']."' , '".$qry->f['top']."' , '".$qry->f['lft']."' , '".$qry->f['rgt']."' , '".$qry->f['bdy']."', '".$qry->f['bot']."');\n");
//---screen end----
//-- script_js  start ----

         $del_txt = "delete from sw.script_js where scpt='".$qry->f['bdy']."';\n".$del_txt;
          $qry12 =$data->getSelect("SELECT * from sw.script_js where scpt='".$qry->f['bdy']."'", $rA, 'S', __LINE__ );
   while (! ($qry12->EOF))
          {
           fwrite($handle,"insert into sw.script_js (scpt,js) values ('".$qry12->f['scpt']."','".$qry12->f['js']."');\n");
         $qry12->MoveNext();
         }
//-- script_js  end ----

//-- srv  start ----
  $i=0;
         $del_txt = "delete from sw.srv where snm = '".$header."';\n".$del_txt;
          $qry =$data->getSelect("SELECT * from sw.srv where snm = '".$header."' order by id", $rA, 'S', __LINE__ );
          while (! ($qry->EOF))
          {
          if ($qry->f['bid']=='NULL' || $qry->f['bid']=='')
            $qry->f['bid']='NULL';
          if ($qry->f['typ']=='NULL' || $qry->f['typ']=='')
            $qry->f['typ']='NULL';
          if ($qry->f['ptp']=='NULL' || $qry->f['ptp']=='')
            $qry->f['ptp']='NULL';
         fwrite($handle,"insert into sw.srv(id,snm,act,stl,ufl,tfl,typ,slf,tck,scp,bid,ptp) values (".$qry->f['id']." , '".$qry->f['snm']."' , '".$qry->f['act']."' , '" .$qry->f['stl']."' , '".$qry->f['ufl']."' , '". $qry->f['tfl']."' , ". $qry->f['typ']." , '". $qry->f['slf']."' , '". $qry->f['tck']."','". $qry->f['scp']."',". $qry->f['bid'].",". $qry->f['ptp'].");\n");
//-- srv  end ----
//----menu start-----
          $qry22 = $data->getSelect("SELECT id,ord,TRIM(txt) as txt,srv,wdt,dsts,rmk,cat,tlvl,blvl from sw.menu where srv=".$qry->f['id'].";", $rA, 'S', __LINE__ );
          while (! ($qry22->EOF))
          {
	  fwrite($handle,"insert into sw.menu(id,ord,txt,srv,wdt,dsts,rmk,cat,tlvl,blvl) values (".$qry22->f['id']." , ". $qry22->f['ord']." ,'".$qry22->f['txt']."', ".$qry22->f['srv']." , ".$qry22->f['wdt']." , '" .$qry22->f['dsts']."' , '".$qry22->f['rmk']."' , '". $qry22->f['cat']."' , '". $qry22->f['tlvl']."' , '". $qry22->f['blvl']."' );\n");
//----menu end-----

//----menu_child start-----
          $del_txt = "delete from sw.menu where id=".$qry22->f['id'].";\n".$del_txt;
          $del_txt = "delete from sw.menu_child where c=".$qry22->f['id'].";\n".$del_txt;
         $qry23 =$data->getSelect("SELECT * from sw.menu_child where c=".$qry22->f['id'].";", $rA, 'S', __LINE__ );
	 while (! ($qry23->EOF))
          {
         fwrite($handle,"insert into sw.menu_child(p,c) values (".$qry23->f['p']." , ".$qry23->f['c'].");\n");
         $qry23->MoveNext();
	 }
         $qry22->MoveNext();
	 }
//--menu_child end---
 fwrite($handle,"\n");

//-- admin.act_flow  begin ----
         $del_txt = "delete from admin.act_flow where act= ".$qry->f['id'].";\n".$del_txt;
         $qry13=$data->getSelect("SELECT * from admin.act_flow where act= ".$qry->f['id']."", $rA, 'S', __LINE__ );
                while (!($qry13->EOF)){
                        $qry13->f['nxt']=preg_replace('/\'/','\'\'',$qry13->f['nxt']);
                        $qry13->f['cnd']=preg_replace('/\'/','\'\'',$qry13->f['cnd']);
                        fwrite($handle,"insert into admin.act_flow(act,nxt,fw2,tw2,fgp,tgp,unt,cnd,rmk,kvs,fw1,tw1) values (".$qry->f['id'].",'".$qry13->f['nxt']."' , '".$qry13->f['fw2']."' , '". $qry13->f['tw2']."' ,".$qry13->f['fgp']." ,".$qry13->f['tgp']." ,".$qry13->f['unt']." ,'".$qry13->f['cnd']."' , '". $qry13->f['rmk']."' ,'".$qry13->f['kvs']."' , '". $qry13->f['fw1']."' ,'".$qry13->f['tw1']."' );\n");
                $qry13->MoveNext();
                }
//-- admin.act_flow  end ----

//-- auth.grp_act  begin ----
         $del_txt = "delete from auth.grp_act where act= ".$qry->f['id'].";\n".$del_txt;
	 $qry1=$data->getSelect("SELECT * from auth.grp_act where act= ".$qry->f['id']."", $rA, 'S', __LINE__ );
		while (!($qry1->EOF)){
        		fwrite($handle,"insert into auth.grp_act(grp,act) values (".$qry1->f['grp'].", ".$qry->f['id'].");\n");
		$qry1->MoveNext();
		}
//-- auth.grp_act  end ----
 fwrite($handle,"\n");

//-- admin.act_privil  begin ----
         $del_txt = "delete from admin.act_privil where act= ".$qry->f['id'].";\n".$del_txt;
         $qry14=$data->getSelect("SELECT * from admin.act_privil where act= ".$qry->f['id']."", $rA, 'S', __LINE__ );
                while (!($qry14->EOF)){
                        fwrite($handle,"insert into admin.act_privil(act,obj,evt,typ) values (".$qry->f['id'].",'".$qry14->f['obj']."','".$qry14->f['evt']."','".$qry14->f['typ']."');\n");
                $qry14->MoveNext();
                }
//-- admin.act_privil  end ----
         $qry->MoveNext();
         }
//-- making the tpl,js,script files --
         $qry =$data->getSelect("SELECT bdy from sw.screen where snm = '".$header."'", $rA, 'S', __LINE__ );
	 while (! ($qry->EOF)){
		$tpl= $qry->f['bdy'];
                $del_txt = "delete from sw.script_fnc where scpt='$tpl';\n".$del_txt;
		$qry2=$data->getSelect("SELECT * from sw.script_fnc where scpt= '".$tpl."'", $rA, 'S', __LINE__ );
                  while (!($qry2->EOF)){
                   fwrite($handle,"insert into sw.script_fnc (scpt,fnc)values ('".$qry2->f['scpt']."' , '".$qry2->f['fnc']."');\n");
                   $qry2->MoveNext();
                }

		$qry1=$data->getSelect("SELECT js from sw.script_js where scpt= '".$tpl."'", $rA, 'S', __LINE__ );
                while (!($qry1->EOF)){
		 $js= $qry1->f['js'];
                 $jvscp=$root_path.'js/'.$js.'.js';
                `cp $jvscp $folder`;
		$qry1->MoveNext();
                }
                 $temp=$root_path.'templates/'.$tpl.'.tpl';
		`cp $temp  $folder`;

         $qry->MoveNext();
	 } 
         $qry =$data->getSelect("SELECT distinct scp from sw.srv where snm = '".$header."' and scp is not null and scp!=''", $rA, 'S', __LINE__ );
	 while (! ($qry->EOF))
         {
		$scpt=$qry->f['scp'];
                 $script=$root_path.'scripts/'.$scpt.'.php';
		`cp $script $folder`;
         $qry->MoveNext();
	 } 
//--making the tpl,js,script files end--


//-- groups start----
         $del_txt = "delete from sw.groups where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.groups where snm='".$header."' order by gno", $rA, 'S', __LINE__ );
          while (! ($qry->EOF))
         {
         fwrite($handle,"insert into sw.groups(gno,snm,kwnm) values(".$qry->f['gno']." , '".$qry->f['snm']."' , '".$qry->f['kwnm']."');\n");
         $qry->MoveNext();
         }
//--groups end---
 fwrite($handle,"\n");

//-- widget start--
         $del_txt = "delete from sw.widget where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.widget where snm='".$header."' order by wno", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
         fwrite($handle,"insert into sw.widget(wno,wnm,snm,fmt,tip,dfl,inn,nvl,ccs,wtp) values (".$qry->f['wno']." , '".$qry->f['wnm']."' , '".$qry->f['snm']."' , '".$qry->f['fmt']."' , '".$qry->f['tip']."' , '".$qry->f['dfl']."' , '".$qry->f['inn']."' , '".$qry->f['nvl']."','".$qry->f['ccs']."','".$qry->f['wtp']."');\n");
         $qry->MoveNext();
         }
 fwrite($handle,"\n");
//--widget ends--
//-- widget Parameter start--
         $del_txt = "delete from sw.widprm where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.widprm where snm='".$header."' ", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
if ($qry->f['av']=='NULL' || $qry->f['av']=='')  $qry->f['av']='NULL';
if ($qry->f['acg']=='NULL' || $qry->f['acg']=='')  $qry->f['acg']='NULL';
         fwrite($handle,"insert into sw.widprm(wnm,snm,stp,av,ac,acg,ss,lf,bf,agf,pf,pl,sf,op,ssrv) values ('".$qry->f['wnm']."' , '".$qry->f['snm']."', '".$qry->f['stp']."' , ".$qry->f['av']." , '".$qry->f['ac']."' , ".$qry->f['acg'].", '".$qry->f['ss']."', ".$qry->f['lf'].",".$qry->f['bf'].", ".$qry->f['agf'].",".$qry->f['pf'].", ".$qry->f['pl'].",".$qry->f['sf'].",'".$qry->f['op']."','".$qry->f['ssrv']."');\n");
         $qry->MoveNext();
         }
 fwrite($handle,"\n");
//--widget Parameter ends--

//-- grp_wid start --
         $del_txt = "delete from sw.grp_wid where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.grp_wid where snm='".$header."' order by gno", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
if ($qry->f['col']=='NULL' or $qry->f['col']=='')
{
$qry->f['col']=-1;
}
         fwrite($handle,"insert into sw.grp_wid(gno,wnm,snm,vno,col) values (".$qry->f['gno']." , '".$qry->f['wnm']."' , '".$qry->f['snm']."' , ".$qry->f['vno']." , '".$qry->f['col']."');\n");
         $qry->MoveNext();
         }
//-- grp_wid end ---
 fwrite($handle,"\n");

//-- wid_sts start --
         $del_txt = "delete from sw.wid_sts where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.wid_sts where snm='".$header."' order by wnm,mod", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
         fwrite($handle,"insert into sw.wid_sts(wnm,snm,mod,v,s) values ('".$qry->f['wnm']."' , '".$qry->f['snm']."' , '".$qry->f['mod']."' , '".$qry->f['v']."' , '".$qry->f['s']."');\n");
         $qry->MoveNext();
         }
//-- wid_sts end ---
 fwrite($handle,"\n");

//--- grd_dtl start ---
         $del_txt = "delete from sw.grd_dtl where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.grd_dtl where snm='".$header."' order by wnm, col", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
         fwrite($handle,"insert into sw.grd_dtl(wnm,snm,col,vf,sf,af,typ,agf,ttl,wdt,cv,sv,csf,awd,ac) values ('".$qry->f['wnm']."' , '".$qry->f['snm']."' , ".$qry->f['col']." , '".$qry->f['vf']."' , '".$qry->f['sf']."' , '".$qry->f['af']."' , '".$qry->f['typ']."' , '".$qry->f['agf']."'  , '".$qry->f['ttl']."' , ".$qry->f['wdt']." , ".$qry->f['cv']." , '".$qry->f['sv']."' ,'".$qry->f['csf']."' , '".$qry->f['awd']."' , '".$qry->f['ac']."' );\n");
         $qry->MoveNext();
         }
//--- grd_dtl end ----
 fwrite($handle,"\n");

//--- grd_hdr start ---
         $del_txt = "delete from sw.grd_hdr where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.grd_hdr where snm='".$header."' order by wnm", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
         $qry->f['html']=preg_replace('/\'/','\'\'',$qry->f['html']);
         fwrite($handle,"insert into sw.grd_hdr(wnm,snm,html,txt) values ('".$qry->f['wnm']."' , '".$qry->f['snm']."' ,'".$qry->f['html']."','".$qry->f['txt']."' );\n");
         $qry->MoveNext();
         }
//--- grd_hdr end ----
 fwrite($handle,"\n");

//-- sw.scrtrg start--
         $del_txt = "delete from sw.scrtrg where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.scrtrg where snm='".$header."'", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
          $qry->f['fnc']=preg_replace('/\'/','\'\'',$qry->f['fnc']);
         fwrite($handle,"insert into sw.scrtrg(snm,wnm,typ,evt,fnc) values('".$qry->f['snm']."' , '".$qry->f['wnm']."','".$qry->f['typ']."','".$qry->f['evt']."','".$qry->f['fnc']."');\n");
         $qry->MoveNext();
         }
//-- action_grp end--
 fwrite($handle,"\n");
//-- action_grp start--
         $del_txt = "delete from sw.action_grp where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.action_grp where snm='".$header."'", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
         fwrite($handle,"insert into sw.action_grp(snm,act,grp) values('".$qry->f['snm']."' , '".$qry->f['act']."',".$qry->f['grp'].");\n");
         $qry->MoveNext();
         }
//-- action_grp end--
 fwrite($handle,"\n");

 //-- exeq start--
          $del_txt = "delete from sw.exeq where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.exeq where snm='".$header."' order by evt,ord", $rA, 'S', __LINE__ ); 
	  while (! ($qry->EOF))
           {
		$qry->f['str']=preg_replace('/\'/','\'\'',$qry->f['str']);
           if ($qry->f['wnm']=='' )
           {
            fwrite($handle,"insert into sw.exeq(snm,wnm,evt,ord,typ,str,rwl) values ('".$qry->f['snm']."',NULL,'".$qry->f['evt']."',".$qry->f['ord'].",'".$qry->f['typ']."','".$qry->f['str']."','".$qry->f['rwl']."');\n");
           }
           else
           {
             fwrite($handle,"insert into sw.exeq(snm,wnm,evt,ord,typ,str,rwl) values ('".$qry->f['snm']."','".$qry->f['wnm']."','".$qry->f['evt']."',".$qry->f['ord'].",'".$qry->f['typ']."','".$qry->f['str']."','".$qry->f['rwl']."');\n");
           }
           $qry->MoveNext();
           }
//-- exe_qry end--
 fwrite($handle,"\n");
//-- group view start--
         $del_txt = "delete from sw.grp_view where snm='".$header."';\n".$del_txt;
         $qry =$data->getSelect("SELECT * from sw.grp_view where snm='".$header."' order by gno,vno  ", $rA, 'S', __LINE__ );
         while (! ($qry->EOF))
         {
          $qry->f['str']=preg_replace('/\'/','\'\'',$qry->f['str']);
          if ($qry->f['opn']=='') $qry->f['opn']='f';
 fwrite($handle,"insert into sw.grp_view(gno,vno,snm,str,ttl,did,opn,obo,obf) values(".$qry->f['gno']." ,".$qry->f['vno'].",'".$qry->f['snm']."','".$qry->f['str']."','".$qry->f['ttl']."','".$qry->f['did']."','".$qry->f['opn']."','".$qry->f['obo']."','".$qry->f['obf']."');\n");

         $qry->MoveNext();
         }
 fwrite($handle,"\n");
//--group view ends--
           $contents  = file_get_contents($folder.$header.'.sql');
           fclose($handle);
           while(1){
               if (strpos($contents,',,') !== false) {
                    $contents    = preg_replace("/,,/", ",null,", $contents);}
                else {
                  break; }
           }
           $contents    = preg_replace('/, \'\' /', ",NULL", $contents);
           $contents    = preg_replace('/,  /', ",NULL", $contents);
           $handle=fopen($folder.$header.'.sql','w+');
           fwrite($handle,$del_txt.$contents);
           fclose($handle);
	   $shellCmd = 'tar -jcvf '.$sc_fldr.''.$snm.'.tar.bz2 '.$sc_fldr.$snm;
	   //echo '<br>'.$shellCmd.'<br>';
	   `$shellCmd`;
	   $rA['__Notice'] = $snm;
           //header('Location: screens/'.$snm.'.tar.bz2');

?>
