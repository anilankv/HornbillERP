<?php
   include_once('classes/db/MySQL.php');
   class oss extends MySQL {
      var $deptFields = array('id' => 'id', 'name' => 'name', 'icon' => 'icon', 'script' => 'script');
      var $serFields = array('id' => 'id', 'name' => 'name', 'icon' => 'icon', 'script' => 'script');
      var $db_connect_id;
      var $query_result;
      var $in_transaction = 0;
      var $row = array();
      var $rowset = array();
      var $rownum = array();
      var $num_queries = 0;
      var $userdata = array() ;
      function oss($host, $port, $database, $user, $password) {
         $this->MySQL($host, $port, $database, $user, $password);
         $rs = $this->selectSet("select user_id, username from  users where username = '$user';") ;
         if($rs->recordCount() == 0){
           $this->close() ;
           if( !(isset($_SESSION)))session_start();
           unset($_SESSION);
           session_destroy();
           header('Location: index.php');
           return null ;
         }
      }
      function &getGroup($user){
   	$sql="select grp from users where username = '$user'";
           $rs=$this->selectSet($sql);
   	return $rs->f['grp'];
      }
      function &getDept($grp) {
         $sql = " SELECT DISTINCT s.id, s.name, s.icon, s.script FROM section s, service r, grp_service gs WHERE r.sect = s.id AND gs.service = r.id AND gs.grp =$grp ;" ;
         return $this->selectSet($sql);
      }
      function &getService($dept,$grp) {
         $sql = "select id, name, icon, script from service,grp_service  where sect = $dept and grp_service.service=service.id and grp_service.grp=$grp order by id  ;";
         return $this->selectSet($sql);
      }
      function getConfVal() {
         global $oss_conf ;
         $rs =  $this->selectSet("SELECT conf_type, value FROM conf ;");
         while (! ($rs->EOF) ){
            $oss_conf[$rs->f['conf_type']] = $rs->f['value'];
            $rs->MoveNext();
	 }
      }

      function setConfVal( $typ, $val) {
        global $oss_conf ;
        $rs =  $this->selectSet("UPDATE conf SET conf_type = $typ , value = $val ;");
        $oss_conf[$typ] = $val ;
      }

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
      function SelectUsers($usr){
   	$rs=$this->sql_query($usr);
           $result=$this->sql_fetchrowset($rs);
           return $result;
      }
      function sql_close() {
         return $this->conn->Close() ;
      }
   
      function sql_query($query = "", $transaction = FALSE) {
         return selectSet( $query ) ;
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
         $sql = " select * from  users where username = '$usr';";
         $rs = $this->selectSet($sql);
         if($rs->recordCount() == 0){
            $this->close() ;
            if( !(isset($_SESSION)))session_start();
            unset($_SESSION);
            session_destroy();
            header('Location: index.php');
            return false ;
         }  
         if ( $rs->f['user_password'] != md5($pass) ) {
            $this->close() ;
            if( !(isset($_SESSION)))session_start();
            unset($_SESSION);
            session_destroy();
            header('Location: index.php');
            return false ;
         }
         $this->userdata = $rs->f ;
         $this->setTheme() ;
         $session_id = md5(uniqid($user_ip));
         $current_time = time();
         $this->userdata['session_id'] = $session_id;
         $this->userdata['session_ip'] = $user_ip;
         $this->userdata['session_start'] = $current_time;
    
         $id=$this->userdata['user_id'];
         return true;
      }
      function &getUserdata() {
         return $this->userdata ;
      }
      function getGrp() {
        $sql="SELECT name from grp;";
        $rs=$this->selectSet($sql);
        return $rs;
      }
      function insertsignup($userid,$usr_act,$buyer,$paswd,$grp) {
        $sql="INSERT into users (user_id, user_active, username, user_password,grp) values ('$userid','$usr_act','$buyer','$paswd','$grp');";
        $rs=$this->selectSet($sql);
        return $rs;
      }
      function updatesignup($user_id,$usr_act,$buyer,$paswd,$grp,$user_id_h) {
        $sql="UPDATE users set user_id='$user_id', user_active='$usr_act', username='$buyer', user_password='$paswd',grp='$grp' where user_id='$user_id_h';";
        $rs=$this->selectSet($sql);
        return $rs;
      }
      function getUser($user) {
        $sql="SELECT user_id, grp from users where username='$user';";
        $rs=$this->selectSet($sql);
        return $rs;
      }
      function getmsgs($user_id) {
        $sql="SELECT count(*) as count from privmsgs where to_usr='$user_id' and sts=0;";
        $rs=$this->selectSet($sql);
        return $rs;
      }
      function gettask_count($user_id) {
        $sql="SELECT count(*) as count from task where e_id='$user_id' and sts=0;";
        $rs=$this->selectSet($sql);
        return $rs;
      }
   
      function getUsernames()  {
        $sql="SELECT username from users ;";
        $rs=$this->selectSet($sql);
        return $rs ;
     }
   }
?>
