<?php
//function get_db_stat($mode) {
//   global $db;
//   return false;
//}

//function get_userdata($user, $force_str = false) {
//   global $db;
//}

//function make_jumpbox($action, $match_forum_id = 0) {
//   global $template, $userdata, $lang, $db, $nav_links, $phpEx, $SID;
//      $boxstring = '<select name="' . POST_FORUM_URL . '" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'jumpbox\'].submit() }"><option value="-1">' . $lang['Select_forum'] . '</option>';
//
//      $forum_rows = array();
//
//      if ( $total_forums = count($forum_rows) )
//      {
//         for($i = 0; $i < $total_categories; $i++)
//         {
//            $boxstring_forums = '';
//            for($j = 0; $j < $total_forums; $j++)
//            {
//               if ( $forum_rows[$j]['cat_id'] == $category_rows[$i]['cat_id'] && $forum_rows[$j]['auth_view'] <= AUTH_REG )
//               {
//            }
//
//            if ( $boxstring_forums != '' )
//            {
//               $boxstring .= '<option value="-1">&nbsp;</option>';
//               $boxstring .= '<option value="-1">' . $category_rows[$i]['cat_title'] . '</option>';
//               $boxstring .= '<option value="-1">----------------</option>';
//               $boxstring .= $boxstring_forums;
//            }
//         }
//      }
//
//      $boxstring .= '</select>';
//   }
//   else
//   {
//      $boxstring .= '<select name="' . POST_FORUM_URL . '" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'jumpbox\'].submit() }"></select>';
//   }
//
//   if ( !empty($SID) )
//   {
//      $boxstring .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
//   }
//
//   $template->set_filenames(array(
//      'jumpbox' => 'jumpbox.tpl')
//   );
//   $template->assign_vars(array(
//      'L_GO' => $lang['Go'],
//      'L_JUMP_TO' => $lang['Jump_to'],
//      'L_SELECT_FORUM' => $lang['Select_forum'],
//
//      'S_JUMPBOX_SELECT' => $boxstring,
//      'S_JUMPBOX_ACTION' => append_sid($action))
//   );
//   $template->assign_var_from_handle('JUMPBOX', 'jumpbox');
//
//   return;
//}

function init_userprefs($userdata)
{
   global $board_config, $images;
   global $template, $lang, $phpEx ;
   global $nav_links;

   if ( !file_exists(@oss_realpath(getcwd() . '/ol/lang_main.'.$phpEx)) )
   {
      $board_config['default_lang'] = 'english';
   }
   $language =  'english';
   include(getcwd() . '/ol/lang/lang_main.' . $phpEx);

   if ( defined('IN_ADMIN') )
   {
      if( !file_exists(@oss_realpath(getcwd() . '/ol/lang_admin.'.$phpEx)) )
      {
         $board_config['default_lang'] = 'english';
      }

      include(getcwd() . '/ol/lang/lang_admin.' . $phpEx);
   }

   setup_style();
   return;
}

function setup_style()
{
   global $template, $images, $root_path;
   include_once( getcwd() . '/ol/classes/template.php');
   $template_path = 'templates/' ;
   $template = new Template($root_path . $template_path ); 
   if ( $template ) {
      $current_template_path = $template_path ;
      @include($root_path  . $template_path . '/' . $template_name . '.cfg'); 
   } 
}

function decode_ip($int_ip)
{
   $hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
   return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

function create_date($format, $gmepoch, $tz)
{
   global $board_config, $lang;
   static $translate;

   if ( empty($translate) && $board_config['default_lang'] != 'english' )
   {
      @reset($lang['datetime']);
      while ( list($match, $replace) = @each($lang['datetime']) )
      {
         $translate[$match] = $replace;
      }
   }

   return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
}

function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE)
{
   global $lang;

   $total_pages = ceil($num_items/$per_page);

   if ( $total_pages == 1 )
   {
      return '';
   }

   $on_page = floor($start_item / $per_page) + 1;

   $page_string = '';
   if ( $total_pages > 10 )
   {
      $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

      for($i = 1; $i < $init_page_max + 1; $i++)
      {
         $page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
         if ( $i <  $init_page_max )
         {
            $page_string .= ", ";
         }
      }

      if ( $total_pages > 3 )
      {
         if ( $on_page > 1  && $on_page < $total_pages )
         {
            $page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

            $init_page_min = ( $on_page > 4 ) ? $on_page : 5;
            $init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

            for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
            {
               $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
               if ( $i <  $init_page_max + 1 )
               {
                  $page_string .= ', ';
               }
            }

            $page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
         }
         else
         {
            $page_string .= ' ... ';
         }

         for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
         {
            $page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>'  : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
            if( $i <  $total_pages )
            {
               $page_string .= ", ";
            }
         }
      }
   }
   else
   {
      for($i = 1; $i < $total_pages + 1; $i++)
      {
         $page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . append_sid($base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) ) . '">' . $i . '</a>';
         if ( $i <  $total_pages )
         {
            $page_string .= ', ';
         }
      }
   }

   if ( $add_prevnext_text )
   {
      if ( $on_page > 1 )
      {
         $page_string = ' <a href="' . append_sid($base_url . "&amp;start=" . ( ( $on_page - 2 ) * $per_page ) ) . '">' . $lang['Previous'] . '</a>&nbsp;&nbsp;' . $page_string;
      }

      if ( $on_page < $total_pages )
      {
         $page_string .= '&nbsp;&nbsp;<a href="' . append_sid($base_url . "&amp;start=" . ( $on_page * $per_page ) ) . '">' . $lang['Next'] . '</a>';
      }

   }

   $page_string = $lang['Goto_page'] . ' ' . $page_string;

   return $page_string;
}

function oss_preg_quote($str, $delimiter)
{
   $text = preg_quote($str);
   $text = str_replace($delimiter, '\\' . $delimiter, $text);
   
   return $text;
}

function obtain_word_list(&$orig_word, &$replacement_word)
{
   return true;
}

function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '') {
   if(defined('HAS_DIED'))
   {
      die("message_die() was called multiple times. This isn't supposed to happen. Was message_die() used in page_tail.php?");
   }
   define('HAS_DIED', 1);
   exit;
}

function oss_realpath($path)
{
   global $phpEx;

   return (!@function_exists('realpath') || !@realpath(getcwd() . '/ol/inc/functions.'.$phpEx)) ? $path : @realpath($path);
}

//function redirect($url)
//{
//   global $db, $board_config;
//
//   if (!empty($db))
//   {
//      $db->sql_close();
//   }
//
//   $server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
//   $server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
//   $server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) : '';
//   $script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
//   $script_name = ($script_name == '') ? $script_name : '/' . $script_name;
//   $url = preg_replace('#^\/?(.*?)\/?$#', '/\1', trim($url));
//
//   if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
//   {
//      header('Refresh: 0; URL=' . $server_protocol . $server_name . $server_port . $script_name . $url);
//      echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><meta http-equiv="refresh" content="0; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be redirected</div></body></html>';
//      exit;
//   }
//
//   // Behave as per HTTP/1.1 spec for others
//   header('Location: ' . $server_protocol . $server_name . $server_port . $script_name . $url);
//   exit;
//}

?>
