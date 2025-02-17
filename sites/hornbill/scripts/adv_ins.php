<?php
      $template->set_filenames(array(
     'adv_ins' =>'air_rep.tpl')
  );
 $tid = isset($_GET['tid']) ? $_GET['tid']:'';

$qry1="select distinct * from qa.insp_rpt('$tid');";
$rs1 = $data->selectSet($qry1);

$qry2="select distinct a.id,a.gno,a.dtd as grdt,b.cno,b.dtd as cardt,c.pno,c.pdt,f.nam||f.adr1 as sup,d.ain,d.armk from store.gr a,pur.mr b,pur.po c,qa.tst_crtf d,pur.mr_det e,pur.vndr f where f.id=c.vid and e.mid=b.id and c.id=e.rid and a.rid=b.id and a.rtyp=1 and d.rid=a.id and d.id='$tid'";
$rs2 = $data->selectSet($qry2);

$qry3="select to_char(otm, 'DD-MM-YYYY') as dt,uid,gid from log.enthst where etp=1304and act='ADD' and kv='$tid';";
$rs3 = $data->selectSet($qry3);
$adt=$rs3->f['dt'];
$auid=$rs3->f['uid'];
$ades=$rs3->f['gid'];
$qry4="select nam from org.employee where eno=(select eno from auth.usr where id='$auid')";
$rs4 = $data->selectSet($qry4);
$addby=$rs4->f['nam'];
$qry5="select nam as dsg from auth.grp where id='$ades'";
$rs5 = $data->selectSet($qry5);
$desg=$rs5->f['dsg'];

$qry6="select to_char(otm, 'DD-MM-YYYY') as dt,uid,gid from log.enthst where etp=1304and act='VRF' and kv='$tid';";
$rs6 = $data->selectSet($qry6);
$vdt=$rs6->f['dt'];
$vuid=$rs6->f['uid'];
$vdes=$rs6->f['gid'];
$qry7="select nam from org.employee where eno=(select eno from auth.usr where id='$vuid')";
$rs7 = $data->selectSet($qry7);
$vrfby=$rs7->f['nam'];
$qry8="select nam as dsg from auth.grp where id='$vdes'";
$rs8 = $data->selectSet($qry8);
$desg2=$rs8->f['dsg'];
$template->assign_vars(array(
                "rcno"=> $rs2->f['cno'],
                "rdt"=> $rs2->f['cardt'],
                "gno"=> $rs2->f['gno'],
                "gdt"=> $rs2->f['grdt'],
                "pno"=> $rs2->f['pno'],
                "pdt"=> $rs2->f['pdt'],
                "sup"=> $rs2->f['sup'],
                "insrmk"=> $rs2->f['armk'],
                "mcod"=> $rs1->f['pmcod'],
                "mdesc"=> $rs1->f['pdsc'],
               "qrcv"=> $rs1->f['prqty'],
               "qacp"=> $rs1->f['paqty'],
               "qrej"=> $rs1->f['prjqty'],
               "name1"=>$addby,
                "dt1"=>$adt,
                "dsg1"=>$desg,
                "name2"=>$vrfby,
                "dt2"=>$vdt,
                "dsg2"=>$desg2   
));
               



$template->pparse("air_rep");
?>


