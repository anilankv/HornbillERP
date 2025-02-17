<?php
   $rA['_qry']  = '';
   $ng = $d_g['gid'] ;
   if ($ng != $gid_g ) {
      $sql = "SELECT g.id, g.dsv FROM open.grp g, open.usr_grp ug WHERE g.id =$ng and ug.grp = $ng and ug.usr=$uid_g " ;
      $sql .= " UNION SELECT u.grp, 0 FROM open.usr u WHERE u.id=$uid_g and u.grp=$ng  ;" ;
      $rA['_qry']  = $sql ;
      $rs = $data->getSelect($sql, $rA, 'S', __LINE__) ;
      if ( !($rs->EOF))  {
         $_SESSION['webdbUsergroup'] = $ng ; 
         $data->userdata['grp'] = $ng ;
         $gid_g = $ng ;
         $_SESSION['gid'] = $ng;
         $rA['srvsts']  = $rs->f['dsv'] ;
         $lq = "INSERT INTO log.ssn ( usr, grp, unt, rip, rfr, uag, ftm ) VALUES ('$uid_g','$gid_g', '$unt_g', '$rip_g', '$rfr_g', '$uA_g', now())" ;
         $sid_g=$data->getSelect( $lq . "  RETURNING id ", $rA, 'S', __LINE__ )->f['id'] ;
         $_SESSION['sid'] = $sid_g ;
         if( $_SESSION['post_based'] ) {
            $q = "select p.id, p.dsg, p.unt, p.dep, p.gid, p.rof, p.svc, p.sbu from org.post p where p.gid='$ng'" ;
            $rs = $data->getSelect( $q, $rA, 'L', __LINE__ );
	    if(!($rs->EOF)) {
               $_SESSION['pst_s'] = $rs->f['id'] ; // Sub unit of employee post
               $_SESSION['unt_sp'] = $rs->f['dep'] ; // Unit of session post
               $_SESSION['dsg_sp'] = $rs->f['dsg'] ; // Designation of session post
               $_SESSION['dep_sp'] = $rs->f['dep'] ; // Department of session post
               $_SESSION['gid_sp'] = $rs->f['gid'] ; // Roll of session post
               $_SESSION['rof_sp'] = $rs->f['rof'] ; // Reporting session of employee post
               //$_SESSION['sof_sp'] = $rs->f['sof'] ; // Supervisor session of employee post
               $_SESSION['svc_sp'] = $rs->f['svc'] ; // Svc of session post
               $_SESSION['sbu_sp'] = $rs->f['sbu'] ; // Sub unit of session post
            } else {
               $_SESSION['pst_s'] = $_SESSION['pst_e'] ;
               $_SESSION['unt_sp'] = $_SESSION['unt_ep'] ;
               $_SESSION['dsg_sp'] = $_SESSION['dsg_ep'] ;
               $_SESSION['dep_sp'] = $_SESSION['dep_ep'] ;
               $_SESSION['gid_sp'] = $_SESSION['gid_ep'] ;
               $_SESSION['rof_sp'] = $_SESSION['rof_ep'] ;
               //$_SESSION['sof_sp'] = $_SESSION['sof_ep'] ;
               $_SESSION['svc_sp'] = $_SESSION['svc_ep'] ;
               $_SESSION['sbu_sp'] = $_SESSION['sbu_ep'] ;
            }
         }
      }
   }
?>
