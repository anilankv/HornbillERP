<?php
function session_begin($user_id, $user_ip, $page_id, $auto_create = 0, $enable_autologin = 0) {
//   global $db, $board_config;
//   global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;
//
//   $cookiename = $board_config['cookie_name'];
//   $cookiepath = $board_config['cookie_path'];
//   $cookiedomain = $board_config['cookie_domain'];
//   $cookiesecure = $board_config['cookie_secure'];
//
//   if(isset($HTTP_COOKIE_VARS[$cookiename . '_sid'])||isset($HTTP_COOKIE_VARS[$cookiename . '_data']) ) {
//      $session_id = isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) ? $HTTP_COOKIE_VARS[$cookiename . '_sid'] : '';
//      $sessiondata = isset($HTTP_COOKIE_VARS[$cookiename . '_data']) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$cookiename . '_data'])) : array();
//      $sessionmethod = SESSION_METHOD_COOKIE;
//   } else {
//      $sessiondata = array();
      $session_id = ( isset($HTTP_GET_VARS['sid']) ) ? $HTTP_GET_VARS['sid'] : '';
//      $sessionmethod = SESSION_METHOD_GET;
//   }
//   $last_visit = 0;
   $current_time = time();
//   $expiry_time = $current_time - $board_config['session_length'];
//
//   if ( $user_id != ANONYMOUS ) {
//      $auto_login_key = $userdata['user_password'];
//      if ( $auto_create ) {
//         if ( isset($sessiondata['autologinid']) && $userdata['user_active'] ) {
//            if( $sessiondata['autologinid'] == $auto_login_key ) {
//               $login = 1;
//               $enable_autologin = 1;
//            } else {
//               $login = 0; 
//               $enable_autologin = 0; 
//               $user_id = $userdata['user_id'] = ANONYMOUS;
//            }
//         } else {
//            $login = 0;
//            $enable_autologin = 0;
//            $user_id = $userdata['user_id'] = ANONYMOUS;
//         }
//      } else {
//         $login = 1;
//      }
//   } else {
//      $login = 0;
//      $enable_autologin = 0;
//   }
//   preg_match('/(..)(..)(..)(..)/', $user_ip, $user_ip_parts);
//
//   $sql = "SELECT ban_ip, ban_userid, ban_email FROM " . BANLIST_TABLE . " 
//      WHERE ban_ip IN ('" . $user_ip_parts[1] . $user_ip_parts[2] . $user_ip_parts[3] . $user_ip_parts[4] . "', '" . $user_ip_parts[1] . $user_ip_parts[2] . $user_ip_parts[3] . "ff', '" . $user_ip_parts[1] . $user_ip_parts[2] . "ffff', '" . $user_ip_parts[1] . "ffffff')
//         OR ban_userid = $user_id";
//   if ( $user_id != ANONYMOUS ) {
//      $sql .= " OR ban_email LIKE '" . str_replace("\'", "''", $userdata['user_email']) . "' 
//         OR ban_email LIKE '" . substr(str_replace("\'", "''", $userdata['user_email']), strpos(str_replace("\'", "''", $userdata['user_email']), "@")) . "'";
//   }
//   if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) ) {
//      message_die(CRITICAL_ERROR, 'Could not obtain ban information', '', __LINE__, __FILE__, $sql);
//   }
//
//   if ( $rs->f['ban_ip'] || $rs->f['ban_userid'] || $rs->f['ban_email'] ) {
//         message_die(CRITICAL_MESSAGE, 'You_been_banned');
//   }
//
//   $sql = "UPDATE " . SESSIONS_TABLE . "
//      SET session_user_id = $user_id, session_start = $current_time, session_time = $current_time, session_page = $page_id, session_logged_in = $login
//      WHERE session_id = '" . $session_id . "' 
//         AND session_ip = '$user_ip'";
//   if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) || !($data->sql_affectedrows()) ) {
//      $session_id = md5(uniqid($user_ip));
//      $sql = "INSERT INTO " . SESSIONS_TABLE . "
//         (session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in)
//         VALUES ('$session_id', $user_id, $current_time, $current_time, '$user_ip', $page_id, $login)";
//      if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) ) {
//         message_die(CRITICAL_ERROR, 'Error creating new session', '', __LINE__, __FILE__, $sql);
//      }
//   }
//
//   if ( $user_id != ANONYMOUS ) { ( 
//      $userdata['user_session_time'] > $expiry_time && $auto_create ) ? $userdata['user_lastvisit'] : ( 
//      $last_visit = ( $userdata['user_session_time'] > 0 ) ? $userdata['user_session_time'] : $current_time; 
//      $sql = "UPDATE " . USERS_TABLE . " 
//         SET user_session_time = $current_time, user_session_page = $page_id, user_lastvisit = $last_visit
//         WHERE user_id = $user_id";
//      if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) ) {
//         message_die(CRITICAL_ERROR, 'Error updating last visit time', '', __LINE__, __FILE__, $sql);
//      }

//      $userdata['user_lastvisit'] = $last_visit;
//
//      $sessiondata['autologinid'] = ( $enable_autologin && $sessionmethod == SESSION_METHOD_COOKIE ) ? $auto_login_key : '';
//      $sessiondata['userid'] = $user_id;
//   }

   $userdata['session_id'] = $session_id;
   $userdata['session_ip'] = $user_ip;
//   $userdata['session_user_id'] = $user_id;
//   $userdata['session_logged_in'] = $login;
//   $userdata['session_page'] = $page_id;
   $userdata['session_start'] = $current_time;
   $userdata['session_time'] = $current_time;

//   setcookie($cookiename . '_data', serialize($sessiondata), $current_time + 31536000, $cookiepath, $cookiedomain, $cookiesecure);
//   setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure);

//   $SID = 'sid=' . $session_id;

   return $userdata;
}

function session_pagestart($user_ip, $thispage_id) {
//   global $db, $lang, $board_config;
   global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;

   $cookiename = 'oss_mysql' ;
   $cookiepath = '/' ;
   $cookiedomain = '' ;
   $cookiesecure = 0 ;

   $current_time = time();
   unset($userdata);

   if ( isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) || isset($HTTP_COOKIE_VARS[$cookiename . '_data']) ) {
      $sessiondata = isset( $HTTP_COOKIE_VARS[$cookiename . '_data'] ) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$cookiename . '_data'])) : array();
      $session_id = isset( $HTTP_COOKIE_VARS[$cookiename . '_sid'] ) ? $HTTP_COOKIE_VARS[$cookiename . '_sid'] : '';
      $sessionmethod = SESSION_METHOD_COOKIE;
   } else {
      $sessiondata = array();
      $session_id = ( isset($HTTP_GET_VARS['sid']) ) ? $HTTP_GET_VARS['sid'] : '';
      $sessionmethod = SESSION_METHOD_GET;
   }
//   if ( !empty($session_id) )
//   {
//      $sql = "SELECT u.*, s.*
//         FROM " . SESSIONS_TABLE . " s, " . USERS_TABLE . " u
//         WHERE s.session_id = '$session_id'
//            AND u.user_id = s.session_user_id";
//      if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) ) {
//         message_die(CRITICAL_ERROR, 'Error doing DB query userdata row fetch', '', __LINE__, __FILE__, $sql);
//      }
//
//      $userdata = $rs->f ;
//
//      if ( isset($userdata['user_id']) ) {
//         $ip_check_s = substr($userdata['session_ip'], 0, 6);
//         $ip_check_u = substr($user_ip, 0, 6);
//
//         if ($ip_check_s == $ip_check_u) {
//            $SID = ($sessionmethod == SESSION_METHOD_GET || defined('IN_ADMIN')) ? 'sid=' . $session_id : '';
//
//            if ( $current_time - $userdata['session_time'] > 60 ) {
//               $sql = "UPDATE " . SESSIONS_TABLE . " 
//                  SET session_time = $current_time, session_page = $thispage_id 
//                  WHERE session_id = '" . $userdata['session_id'] . "'";
//               if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) ) {
//                  message_die(CRITICAL_ERROR, 'Error updating sessions table', '', __LINE__, __FILE__, $sql);
//               }
//
//               if ( $userdata['user_id'] != ANONYMOUS ) {
//                  $sql = "UPDATE " . USERS_TABLE . " 
//                     SET user_session_time = $current_time, user_session_page = $thispage_id 
//                     WHERE user_id = " . $userdata['user_id'];
//                  if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) ) {
//                     message_die(CRITICAL_ERROR, 'Error updating sessions table', '', __LINE__, __FILE__, $sql);
//                  }
//               }
//
//               $expiry_time = $current_time - $board_config['session_length'];
//               $sql = "DELETE FROM " . SESSIONS_TABLE . " WHERE session_time < $expiry_time 
//                     AND session_id <> '$session_id'";
//               if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) ) {
//                  message_die(CRITICAL_ERROR, 'Error clearing sessions table', '', __LINE__, __FILE__, $sql);
//               }
//
//               setcookie($cookiename . '_data', serialize($sessiondata), $current_time + 31536000, $cookiepath, $cookiedomain, $cookiesecure);
//               setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure);
//            }
//
//            return $userdata;
//         }
//      }
//   }
//   $user_id = ( isset($sessiondata['userid']) ) ? intval($sessiondata['userid']) : ANONYMOUS;
//   if ( !($userdata = session_begin($user_id, $user_ip, $thispage_id, TRUE)) ) {
//      message_die(CRITICAL_ERROR, 'Error creating user session', '', __LINE__, __FILE__, 'Critical' );
//        echo 'Error creating user session' ;
//	exit() ;
//   }
//   return $userdata;
}

function session_end($session_id, $user_id) {
//   global $db, $lang, $board_config;
//   global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;
//
//   $cookiename = $board_config['cookie_name'];
//   $cookiepath = $board_config['cookie_path'];
//   $cookiedomain = $board_config['cookie_domain'];
//   $cookiesecure = $board_config['cookie_secure'];
//
//   $current_time = time();
//
//   if ( isset($HTTP_COOKIE_VARS[$cookiename . '_sid']) ) {
//      $session_id = isset( $HTTP_COOKIE_VARS[$cookiename . '_sid'] ) ? $HTTP_COOKIE_VARS[$cookiename . '_sid'] : '';
//      $sessionmethod = SESSION_METHOD_COOKIE;
//   } else {
//      $session_id = ( isset($HTTP_GET_VARS['sid']) ) ? $HTTP_GET_VARS['sid'] : '';
//      $sessionmethod = SESSION_METHOD_GET;
//   }
//
//   $sql = "DELETE FROM " . SESSIONS_TABLE . " WHERE session_id = '$session_id' 
//         AND session_user_id = $user_id";
//   if ( !($rs = $data->getSelect($sql, $rA, 'S', __LINE__ )) ) {
//      message_die(CRITICAL_ERROR, 'Error removing user session', '', __LINE__, __FILE__, $sql);
//   }
//   setcookie($cookiename . '_data', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
//   setcookie($cookiename . '_sid', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
   return true;
}

function append_sid($url, $non_html_amp = false) {
   global $SID;
   if ( !empty($SID) && !preg_match('#sid=#', $url) ) {
      $url .= (( strpos($url, '?') != false ) ? (( $non_html_amp ) ? '&' : '&amp;' ) : '?') . $SID;
   }
   return $url;
}
?>
