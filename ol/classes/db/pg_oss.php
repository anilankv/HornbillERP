<?php
   GLOBAL $root_path ;
   include_once( getcwd() . '/ol/classes/db/Postgres.php');
   class pg_oss extends Postgres {
      var $deptFields = array('id' => 'id', 'nam' => 'nam', 'icon' => 'icon', 'script' => 'script');
      var $serFields = array('id' => 'id', 'nam' => 'nam', 'icon' => 'icon', 'script' => 'script');
      var $db_connect_id;
      var $query_result;
      var $in_transaction = 0;
      var $row = array();
      var $rowset = array();
      var $rownum = array();
      var $num_queries = 0;
      var $userdata = array() ;
      var $client_script = '' ;
      var $body_prop = '' ;
      public function __construct($conn, $usr) {
         $this->pg_oss($conn,$usr) ;
      }
      function pg_oss($conn, $usr ) {
         global $conf, $u_Nm, $rA  ;
         $this->Postgres($conn);
         if ( !defined( 'IN_INSTALL' ) ) {
            if( $usr == '' ) {
               $this->close() ;
               if( !(isset($_SESSION)))session_start();
               unset($_SESSION);
               session_destroy();
               header('Location: index.php');
               return null ;
            }
            if ( $usr != $conf['dbuser'] ) {
               $sql = "select u.id, u.nam, u.unt, u.sts, ug.grp, u.sbu from open.usr u, open.usr_grp ug where u.nam='$usr' and u.sts > 0 " ;
               $sql .= " and u.id = ug.usr and ug.grp = u.grp and u.unt = (select id from open.units o where o.unm = '{$u_Nm[0]}' );" ;
//$rA['connect'] = "<P> 1.1 " . __FILE__ . " | " . $sql . " |</P>" ;
//exit ;

               $rs = $this->getSelect( $sql, $rA, 'PG', __LINE__ );
               if($rs->recordCount() == 0){
                  if( !(isset($_SESSION)))session_start();
                  unset($_SESSION);
                  session_destroy();
                  return null ;
               }
               $_SESSION['uid'] = $rs->f['id'] ;
               $_SESSION['unt'] = $rs->f['unt'] ;
               $_SESSION['unm'] = $rs->f['nam'] ;
               $_SESSION['uSt'] = $rs->f['sts'] ;
               $_SESSION['sbu'] = $rs->f['sbu'] ;
               $_SESSION['gid'] = (!empty($_SESSION['gid']) && ($_SESSION['gid'] != $conf['dbusrgrp']) ) ? $_SESSION['gid'] :  $rs->f['grp'] ;
            } else {
               $_SESSION['uid'] = -1 ;
               $_SESSION['gid'] = 0 ;
//               $_SESSION['unt'] = $rs->f['unt'] ;
            }
         }
      }

      function getConfVal() {
         global $rA ;
         if ( !defined( 'IN_INSTALL' ) ) {
            global $oss_conf ;
            $rs =  $this->getSelect("SELECT conf_type, value FROM admin.conf ;", $rA, 'PG', __LINE__);
            while (! ($rs->EOF) ){
               $oss_conf[$rs->f['conf_type']] = $rs->f['value'];
               $rs->MoveNext();
            }
         }
      }
      function getSrvVal($srv) {
         global $gid_g, $uid_g, $rA ;
         $q =  "select s.id,w.nam,w.mod,s.snm,s.act,s.stl,s.ufl,s.tfl,s.typ,w.top,w.lft,w.rgt,w.bdy,s.scp,w.bot,w.icn,s.slf,s.tck,w.eid " ;
         $q .= " from sw.srv s, sw.screen w where s.id ='$srv' and s.snm=w.snm and s.ptp  in (2,3)  UNION " ;
         $q .= " select s.id, null, null, s.snm, s.act, s.stl, s.ufl, s.tfl, s.typ, null, null, null, null, s.scp, null, null, s.slf, " ;
         $q .= " s.tck, null from sw.srv s where s.id = '$srv' and s.snm is null and s.ptp in (2,3);  " ;
         $rs =  $this->getSelect($q, $rA, 'PG', __LINE__);
         if (! ($rs->EOF) ){
            $rs->f['scf'] = ($rs->f['scp'] == '' ) ? 'f' : 't' ;
            if( $rs->f['scf'] == 't' ) $rs->f['scf'] = ( $rs->f['snm'] == ''  ) ? 'l' : 's' ;
//$rA['dbgM']["srv " . $srv] = "<P>" . basename(__FILE__)  . '::' . __LINE__ . " ($q) (" . $rs->RowCount . ") </P>" ;
            return $rs->f ;
         }
         check_srv_auth($uid_g, $srv, $gid_g ) ;
         $q =  "select s.id,w.nam,w.mod,s.snm,s.act,s.stl,s.ufl,s.tfl,s.typ,w.top,w.lft,w.rgt,w.bdy,s.scp,w.bot,w.icn,s.slf,s.tck,w.eid " ;
         $q .= " from sw.srv s, sw.screen w, auth.grp_act g where s.id ='$srv' and s.snm=w.snm and g.act = s.id and g.grp = $gid_g UNION " ;
         $q .= " select s.id, null, null, s.snm, s.act, s.stl, s.ufl, s.tfl, s.typ, null, null, null, null, s.scp, null, null, s.slf, " ;
         $q .= " s.tck, null from sw.srv s, auth.grp_act g where s.id = '$srv' and s.snm is null and g.act = s.id and g.grp = $gid_g  ;" ;
         $rs =  $this->getSelect($q, $rA, 'PG', __LINE__);
//$rA['dbgM' . __LINE__]["srv " . $srv] = "<P>" . basename(__FILE__)  . " ($q) (" . $rs->RowCount . ")(gid $gid_g ) (uid $uid_g)  </P>" ;
         if (! ($rs->EOF) ){
            $rs->f['scf'] = ($rs->f['scp'] == '' ) ? 'f' : 't' ;
            if( $rs->f['scf'] == 't' ) $rs->f['scf'] = ( $rs->f['snm'] == ''  ) ? 'l' : 's' ;
            return $rs->f ;
         } else {
            return false ;
//$rA['dbgM']["srv " . $srv] = "<P>" . basename(__FILE__)  . '::' . __LINE__ . " ($q) (" . $rs->RowCount . ") </P>" ;
         }
      }
//      function getBasSrvDt() {
//         global $rA ;
//         global $srvdt_g ;
//         $sql =  "select s.id, w.nam, w.mod, w.snm, s.act, s.stl, s.ufl, s.tfl, s.typ, w.top, w.lft, w.rgt, w.bdy, " ;
//         $sql .= " w.bot, w.icn, s.slf, w.scf, s.tck, s.bid from sw.srv s, sw.screen w " ;
//         $sql .= " where w.snm ='" . $srvdt_g['snm'] . "' and s.snm='" . $srvdt_g['snm'] . "' ;" ;
//         $rs =  $this->getSelect($sql, $rA, 'PG', __LINE__);
//         if (! ($rs->EOF) ){
//            return $rs->f ;
//         } else {
//            return false ;
//         }
//      }
      function getSelect( $q, &$rA_p, $t='U', $n=0) {
         global $conf ;
         $rs = $this->selectSet( $q ) ;
         if ( !is_object($rs)) {
            if($err = $this->getError()) $rA_p['__Error'] = (empty($rA_p['__Error'])) ? $err : $rA_p['__Error'] ; 
	    $rA_p['__Ecode'] = $t . '-' . $n ; 
            $rA_p['__Notice'] = $this->getNotice() ;
            if (!empty($conf['dbg'])) {
               if($conf['dbg'] > 0 ) $rA_p['_erQ']     = $q;
            }
            echo "(" . json_encode($rA_p) . ")" ;
            $rs = $this->selectSet( "ROLLBACK ;" ) ;
            exit ;
         }
         if (!empty($conf['dbg'])) {
            if(($t == 'S') || ($t == 'X') ) {
               if($conf['dbg'] > 1  ) $rA_p[$t . '-' . $n]     = $q;
	    } else if(($t == 'L') ) {
               if($conf['dbg'] > 2  ) $rA_p[$t . '-' . $n]     = $q;
            } else {
               if($conf['dbg'] > 3  ) $rA_p[$t . '-' . $n]     = $q;
            }
         }
	 return $rs ;
      }
      function setConfVal( $typ, $val) {
         global $rA ;
         global $oss_conf ;
         if ( !is_numeric($val) ) $val =0 ;
         $rs =  $this->getSelect("UPDATE admin.conf SET value = '$val' where  conf_type = '$typ' ;", $rA, 'PG', __LINE__);
         $oss_conf[$typ] = $val ;
      }
      
      function &getGroup($user){
         global $rA ;
         $sql="select grp from auth.usr where nam = '$user'";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs->f['grp'];
      }
//      function &getDept($grp) {
//         global $rA ;
//         $sql = " SELECT DISTINCT s.id, s.nam, s.icon, s.script FROM sw.sect s, sw.srv r, sw.grp_srv gs WHERE r.sect = s.id AND gs.srv = r.id AND gs.grp =$grp ;" ;
//         return $this->getSelect($sql, $rA, 'PG', __LINE__);
//      }
      function getClientScript() {
         return $this->client_script ;
      }
      function setClientScript( $str ) {
         $this->client_script = $str ;
      }
      function getBodyProp() {
         return $this->body_prop ;
      }
      function setBodyProp( $str ) {
         $this->body_prop = $str ;
      }
//      function &getService($dept,$grp) {
//         global $rA ;
//         $sql = "select id, nam, icon, script from sw.srv s, sw.grp_srv gs  where sect = $dept and gs.srv=s.id and gs.grp=$grp order by id  ;";
//         return $this->getSelect($sql, $rA, 'PG', __LINE__);
//      }
      function sql_fetchrow($query_id = 0) {
         if(!$query_id) {
            $query_id = $this->query_result;
         }
         if($query_id) {
            $this->row[$query_id] = @mysql_fetch_array($query_id);
            return $this->row[$query_id];
         } else {
            return false;
         }
      }
      function sql_close() {
         return $this->conn->Close() ;
      }
      function sql_query($query = "", $transaction = FALSE) {
         global $rA ;
         return getSelect( $query, $rA, 'PG', __LINE__ ) ;
      }
      function sql_numrows($rs) {
         return $rs->NumRows() ;
      }
      function sql_affectedrows() {
         return $this->conn->Affected_Rows() ;
      }
      function sql_numfields($rs) {
         return $rs->FieldCount() ;
      }
   
      function setSession($usr, $pass, $user_ip) {
         global $root_path ;
         global $conf, $rA ;
         if ( $usr != $conf['dbuser'] ) {
            $sql = " select * from  auth.usr where nam = '$usr' and sts>'0' ;";
            $rs = $this->getSelect($sql, $rA, 'PG', __LINE__);
            if($rs->recordCount() == 0){
               $this->close() ;
               if( isset($_SESSION)) unset($_SESSION);
               header('Location: ' . $root_path . 'index.php');
               return false ;
            }  
//            if ( $rs->f['pass'] != md5($pass) ) {
//               $this->close() ;
//               if( !(isset($_SESSION)))session_start();
//               unset($_SESSION);
//               session_destroy();
//               header('Location: ' . $root_path . 'index.php');
//               return false ;
//            }
            $this->userdata = $rs->f ;
            $this->userdata['grp'] = ($_SESSION['gid']) ? $_SESSION['gid'] : -1 ;
//            $this->setTheme() ;
            $session_id = md5(uniqid($user_ip));
            $current_time = time();
            if(!empty($_SESSION['gid'])) if ($_SESSION['gid'] != $this->userdata['grp'] ) {
               $sql = "SELECT g.id FROM open.grp g, open.usr_grp ug WHERE g.id =" . $_SESSION['gid'] . " and ug.grp = g.id and ug.usr=" . $this->userdata['id']  ;
               $sql .= " UNION SELECT u.grp FROM open.usr u WHERE u.id=" . $this->userdata['id'] . "  and u.grp= " .  $_SESSION['gid'] ;
               $rs = $this->getSelect($sql, $rA, 'PG', __LINE__) ;
               if ( !($rs->EOF))  {
                  $this->userdata['grp'] = $_SESSION['gid'] ;
               }
            }
         } else {
            $this->userdata['id'] = -1 ;
            $this->userdata['grp'] = 0 ;
            $this->userdata['nam'] = $conf['dbuser'] ;
         }
//         $this->userdata['session_id'] = $session_id;
         $this->userdata['session_ip'] = $user_ip;
//         $this->userdata['session_start'] = $current_time;
         $id=$this->userdata['id'];
         return true;
      }
      function &getUserdata() {
         return $this->userdata ;
      }
      function getGrp() {
         global $rA ;
        $sql="SELECT nam from auth.grp;";
        $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
        return $rs;
      }

      function getuserconfcode($name) {
         global $rA ;
         $sql="SELECT conf_id as code from auth.usr where nam='$name';";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }
      function getlogin_textDtls() {
         global $rA ;
         $sql="SELECT * from  admin.wmsg ; ";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }
      function getName( $table, $id ){
         global $rA ;
         $sql="select nam from $table where id='$id'";
         $rs= $this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs->f['nam'];
      }
      function get_page_help ( $snm ){
         global $rA ;
         $rs = $this->getSelect( " select txt from sw.scrhlp where snm = '$snm' ;", $rA, 'PG', __LINE__ ) ;
         if ( !($rs->EOF) ) return $rs->f['rmk'] ;
         else return '' ;
      }

      function update_help_text ( $sNm ,$txtRmk ){
         global $rA ;
         $sql="UPDATE sw.scrhlp set txt = trim ('$txtRmk')  where id = '$sNm'  ";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }

      function getScripts_Help ($cid ) {
         global $rA ;
         $sql="SELECT s.snm , s.nam , h.txt from sw.scrhlp h, sw.screen s where s.snm ='$cid' order by s.snm;";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }

      function update_login_text ( $txtText ,$home_txt ) {
         global $rA ;
         $l = addslashes( $txtText ) ;
         $h = addslashes( $home_txt ) ;
         $sql ="update admin.wmsg  set login_txt ='$l' , home_txt = '$h'  ;" ;
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }
      function update_user_pass ($txtUName,$txtEmail, $pass ) { 
         global $rA ;
          $sql ="UPDATE auth.usr SET  eml ='$txtEmail' where nam='$txtUName';" ;
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }


      function   show_user_dts ($username) {
         global $rA ;
              $sql="SELECT id, nam ,eml ,( case when sts=0 then 'Non Active' else 'Active' end ) as status from auth.usr where nam='$username';";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }
     
      function   get_all_Users ()  {
         global $rA ;
         $sql="SELECT id, nam ,eml ,( case when sts=0 then 'Non Active'
               else 'Active' end ) as status from sw.usr where sw.grp not in (0,6) order by id ;";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }
      function   get_all_Users_sort_by_Name ()  {
         global $rA ;
         $sql="SELECT id, nam ,eml ,( case when sts=0 then 'Non Active'
            else 'Active' end ) as status from usr sw.where sw.grp not in (0,6) order by nam ;";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }
      function getDBdate() {
         global $rA ;
         $rs=$this->getSelect( "select date(now()) as dt ;" , $rA, 'PG', __LINE__);
         return $rs->f['dt'] ;
      }
      function getid_common ($table,$id,$field,$field_name) {
         global $rA ;
         $sql="SELECT $id as id from $table where $field='$field_name';";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         if( $rs->EOF ) return NULL ;
         return $rs;
      }
      function getUserConfirmDate($uid) {
         global $rA ;
         $rs=$this->getSelect( "select cdt as dt from auth.usr where id = $uid ;" , $rA, 'PG', __LINE__);
         return $rs->f['dt'] ;
      }
      function  check_unique ( $tbl , $fld , $val ){
         global $rA ;
         $sql = " SELECT check_unique ( '$tbl', '$fld','$val' ) as  ret  ";
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs->f['ret'] ;
      }

      function get_current_date () {
         global $rA ;
         $sql= "SELECT current_date as cdt " ;
         $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
         return $rs;
      }
      function  update_faq ($HdnId , $txtQuestion ,$txtAnswer ) {
         global $rA ;
           $q = addslashes( $txtQuestion ) ;
           $a = addslashes( $txtAnswer ) ;
           $sql=" update admin.faq set qstn = '$q' , ans='$a'  where id='$HdnId'  " ;
           $rs=$this->getSelect($sql, $rA, 'PG', __LINE__);
           return $rs ;
      }
      function makePass() {
         $makepass="";
         $syllables="b,C,d,F,g,H,j,K,l,M,n,P,q,R,s,T,v,W,x,Y,z,0,1,2,3,4,5,6,7,8,9";
         $syllable_array=explode(",", $syllables);
         for ($count=1;$count<=9;$count++) {
            $makepass .= sprintf("%s", $syllable_array[round(rand(0,30))] ) ;
         }
         return($makepass);
      }
      function set_user_privil ( $unam ) {
         global $rA ;
         $rs = $this->getSelect( "select id, nam, eml, grp, sts, score from qst.usr where nam='$unam';" , $rA, 'PG', __LINE__) ;
         if (!($rs->EOF)){
            if ( $rs->f['score'] > 0 ){
                $this->getSelect( "UPDATE qst.usr SET grp = '3' where nam='$unam' and grp in ( 4, 7) ;", $rA, 'PG', __LINE__ ) ;
            }
            if ( $rs->f['score'] <= 0 ){
                $this->getSelect( "UPDATE qst.usr SET grp = '7' where nam='$unam' and grp in ( 2, 3, 5 ) ;", $rA, 'PG', __LINE__ ) ;
            }
         }
      } 
   }
?>
