<?php
   //$sql = "select count(*) as count from admin.msg_$gid_g m where m.tim > ( SELECT mvt from auth.usr where id='$uid_g')   ;" ;
   $sql = "select count(*) as c from log.msg m, log.msg_grp g where rfg='t' and m.id=g.mid and g.gid='$gid_g'   ;" ;
   $rs = $data->getSelect( $sql, $rA, 'M', __LINE__ ) ;
   $rA['c'] = $rs->f['c'] ;
   //echo $rs->f['c'] ;
?>
