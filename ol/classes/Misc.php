<?php
   class Misc {
      var $href;
      var $form;
      
//      function Misc() {
//     }

      function isDumpEnabled($all = false) {
         global $conf;
         
         if ($all)
            return ($conf['servers'][$_SESSION['webdbServerID']]['pg_dumpall_path'] !== null 
                        && $conf['servers'][$_SESSION['webdbServerID']]['pg_dumpall_path'] != '');
         else 
            return ($conf['servers'][$_SESSION['webdbServerID']]['pg_dump_path'] !== null 
                        && $conf['servers'][$_SESSION['webdbServerID']]['pg_dump_path'] != '');
      }

      function checkExtraSecurity() {
         global $conf;

         $bad_usernames = array('pgsql', 'postgres', 'root', 'administrator');
         if (!$conf['extra_login_security']) return true;
         elseif ($_SESSION['webdbPassword'] == '') return false;
         else {
            $username = strtolower($_SESSION['webdbUsername']);
            return !in_array($username, $bad_usernames);
         }
      }

      function setHREF() {
         $this->href = '';
         if (isset($_REQUEST['database'])) {
            $this->href .= 'database=' . urlencode($_REQUEST['database']);
            if (isset($_REQUEST['schema']))
               $this->href .= '&amp;schema=' . urlencode($_REQUEST['schema']);
         }
      }

      function setForm() {
         $this->form = '';
         if (isset($_REQUEST['database'])) {
            $this->form .= "<input type=\"hidden\" name=\"database\" value=\"" . htmlspecialchars($_REQUEST['database']) . "\" />\n";
            if (isset($_REQUEST['schema']))
               $this->form .= "<input type=\"hidden\" name=\"schema\" value=\"" . htmlspecialchars($_REQUEST['schema']) . "\" />\n";
         }
      }

      function printVal($str, $type = null, $params = array()) {
         global $lang, $conf;
         
         if (is_null($str))
            return isset($params['null'])
                  ? ($params['null'] === true ? '<i>NULL</i>' : $params['null'])
                  : '';
         
         if (isset($params['map']) && isset($params['map'][$str])) $str = $params['map'][$str];
         
         if (isset($params['clip']) && $params['clip'] === true) {
            $maxlen = isset($params['cliplen']) && is_integer($params['cliplen']) ? $params['cliplen'] : $conf['max_chars'];
            $ellipsis = isset($params['ellipsis']) ? $params['ellipsis'] : $lang['strellipsis'];
            if (strlen($str) > $maxlen) {
               $str = substr($str, 0, $maxlen-1) . $ellipsis;
            }
         }

         $out = '';
         
         switch ($type) {
            case 'int2':
            case 'int4':
            case 'int8':
            case 'float4':
            case 'float8':
            case 'money':
            case 'numeric':
            case 'oid':
            case 'xid':
            case 'cid':
            case 'tid':
               $align = 'right';
               $out = nl2br(htmlspecialchars($str));
               break;
            case 'yesno':
               if (!isset($params['true'])) $params['true'] = $lang['stryes'];
               if (!isset($params['false'])) $params['false'] = $lang['strno'];
            case 'bool':
            case 'boolean':
               if (is_bool($str)) $str = $str ? 't' : 'f';
               switch ($str) {
                  case 't':
                     $out = (isset($params['true']) ? $params['true'] : $lang['strtrue']);
                     $align = 'center';
                     break;
                  case 'f':
                     $out = (isset($params['false']) ? $params['false'] : $lang['strfalse']);
                     $align = 'center';
                     break;
                  default:
                     $out = htmlspecialchars($str);
               }
               break;
            case 'bytea':
               $out = htmlspecialchars(addCSlashes($str, "\0..\37\177..\377"));
               break;
            case 'pre':
               $tag = 'pre';
               $out = htmlspecialchars($str);
               break;
            case 'prenoescape':
               $tag = 'pre';
               $out = $str;
               break;
            case 'nbsp':
               $out = nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($str)));
               break;
            case 'verbatim':
               $out = $str;
               break;
            case 'callback':
               $out = $params['function']($str, $params);
               break;
            default:
               if (preg_match('/(^ |  |\t|\n )/m', $str)) {
                  $tag = 'pre';
                  $class = 'data';
                  $out = htmlspecialchars($str);
               } else {
                  $out = nl2br(htmlspecialchars($str));
               }
         }
         
         if (isset($params['class'])) $class = $params['class'];
         if (isset($params['align'])) $align = $params['align'];
         
         if (!isset($tag) && (isset($class) || isset($align))) $tag = 'div';
         
         if (isset($tag)) {
            $alignattr = isset($align) ? " align=\"{$align}\"" : '';
            $classattr = isset($class) ? " class=\"{$class}\"" : '';            
            $out = "<{$tag}{$alignattr}{$classattr}>{$out}</{$tag}>";
         }

         if (isset($params['lineno']) && $params['lineno'] === true) {
            $lines = explode("\n", $str);
            $num = count($lines);
            if ($num > 0) {
               $temp = "<table>\n<tr><td class=\"{$class}\" style=\"vertical-align: top; padding-right: 10px;\"><pre class=\"{$class}\">";
               for ($i = 1; $i <= $num; $i++) {
                  $temp .= $i . "\n";
               }
               $temp .= "</pre></td><td class=\"{$class}\" style=\"vertical-align: top;\">{$out}</td></tr></table>\n";
               $out = $temp;
            }
            unset($lines);
         }

         return $out;
      }

      function stripVar(&$var) {
         if (is_array($var)) {
            foreach($var as $k => $v) {
               $this->stripVar($var[$k]);
            }      
         }
         else
            $var = stripslashes($var);   
      }
      
      function printTitle($title, $help = null) {
         global $data, $lang;
         
         echo "<h2>";
         $this->printHelp($title, $help);
         echo "</h2>\n";
      }
      
      function printMsg($msg) {
         if ($msg != '') echo "<p class=\"message\">{$msg}</p>\n";
      }

      function &getDatabaseAccessor($database) {
         global $conf;

         $_connection = new Connection(
            $conf['servers'][$_SESSION['webdbServerID']]['host'],
            $conf['servers'][$_SESSION['webdbServerID']]['port'],
            $_SESSION['webdbUsername'],
            $_SESSION['webdbPassword'],
            $database,
	    $conf['dbms']
         );

         $_type = $_connection->getDriver($desc);
	 $type = $conf['servers'][$_SESSION['webdbServerID']]['type'] ;
         include_once( $root_path . 'ol/classes/db/' . $type . '.php');
         $data = new $type($_connection->conn);

         return $data;
      }

      function printHeader($title = '', $script = null) {
         global $appName, $lang, $_no_output, $conf;

         if (!isset($_no_output)) {
            if (isset($conf['use_xhtml']) && $conf['use_xhtml']) {
               echo "<?xml version=\"1.0\" encoding=\"", htmlspecialchars($lang['appcharset']), "\"?>\n";
               echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-Transitional.dtd\">\n";
               echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"{$lang['applocale']}\" lang=\"{$lang['applocale']}\"";
               if (strcasecmp($lang['applangdir'], 'ltr') != 0) echo " dir=\"", htmlspecialchars($lang['applangdir']), "\"";
               echo ">\n";
            } else {
               echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
               echo "<html lang=\"{$lang['applocale']}\"";
               if (strcasecmp($lang['applangdir'], 'ltr') != 0) echo " dir=\"", htmlspecialchars($lang['applangdir']), "\"";
               echo ">\n";
            }
            echo "<head>\n";
            echo "<title>", htmlspecialchars($appName);
            if ($title != '') echo " - {$title}";
            echo "</title>\n";
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset={$lang['appcharset']}\" />\n";
            
            echo "<link rel=\"stylesheet\" href=\"../templates/global.css\" type=\"text/css\" />\n";
            if ($script) echo "{$script}\n";
            echo "</head>\n";
         }
      }

      function printFooter($doBody = true) {
         global $_reload_browser, $_reload_drop_database;

         if ($doBody) {
            if (isset($_reload_browser)) $this->printReload(false);
            elseif (isset($_reload_drop_database)) $this->printReload(true);
            echo "</body>\n";
         }
         echo "</html>\n";
      }

      function printBody($bodyClass = '', $doBody = true ) {
         global $_no_output;         

         if (!isset($_no_output)) {
            if ($doBody) {
               $bodyClass = htmlspecialchars($bodyClass);
               echo "<body", ($bodyClass == '' ? '' : " class=\"{$bodyClass}\"");
               echo ">\n";
            }
         }
      }

      function printReload($database) {
         echo "<script language=\"JavaScript\">\n";
         if ($database)
            echo "\tparent.frames.browser.location.href=\"browser.php\";\n";
         else
            echo "\tparent.frames.browser.location.reload();\n";
         echo "</script>\n";
      }

      function printTabs($tabs, $activetab) {
         global $misc, $conf, $data, $lang;
         
         if (is_string($tabs)) {
            switch ($tabs) {
               case 'database':
               case 'schema':
                  if ($data->hasSchemas() === false) {
                     $this->printTabs($this->getNavTabs('database'),$activetab);
                     $this->printTabs($this->getNavTabs('schema'),$activetab);
                     $_SESSION['webdbLastTab']['database'] = $activetab;
                     return;
                  }
               default:
                  $_SESSION['webdbLastTab'][$tabs] = $activetab;
                  $tabs = $this->getNavTabs($tabs);
            }
         }
         
         echo "<table class=\"tabs\"><tr>\n";
         
         $width = round(100 / count($tabs)).'%';
         
         foreach ($tabs as $tab_id => $tab) {
            $active = ($tab_id == $activetab) ? ' active' : '';
            
            if (!isset($tab['hide']) || $tab['hide'] !== true) {
               $tablink = "<a href=\"" . $this->printVal($tab['url'], 'nbsp') . "\">{$tab['title']}</a>";
               
               echo "<td width=\"{$width}\" class=\"tab{$active}\">";
               
               if (isset($tab['help']))
                  $this->printHelp($tablink, $tab['help']);
               else
                  echo $tablink;
               
               echo "</td>\n";
            }
         }
         
         echo "</tr></table>\n";
      }

      function getNavTabs($section) {
         global $data, $lang, $conf;
         
         $databasevar = isset($_REQUEST['database']) ? 'database=' . urlencode($_REQUEST['database']) : '';
         $schemavar = isset($_REQUEST['schema']) ? '&schema=' . urlencode($_REQUEST['schema']) : '';
         $hide_advanced = ($conf['show_advanced'] === false);
         
         switch ($section) {
            case 'server':
               $hide_users = !$data->isSuperUser($_SESSION['webdbUsername']);
               return array (
                  'databases' => array (
                     'title' => $lang['strdatabases'],
                     'url'   => "all_db.php",
                     'help'  => 'pg.database',
                  ),
                  'users' => array (
                     'title' => $lang['strusers'],
                     'url'   => "users.php",
                     'hide'  => $hide_users,
                     'help'  => 'pg.user',
                  ),
                  'groups' => array (
                     'title' => $lang['strgroups'],
                     'url'   => "groups.php",
                     'hide'  => $hide_users,
                     'help'  => 'pg.group',
                  ),
                  'tablespaces' => array (
                     'title' => $lang['strtablespaces'],
                     'url'   => "tablespaces.php",
                     'hide'  => (!$data->hasTablespaces()),
                     'help'  => 'pg.tablespace',
                  ),
                  'export' => array (
                     'title' => $lang['strexport'],
                     'url'   => "all_db.php?action=export",
                     'hide'  => (!$this->isDumpEnabled()),
                  ),
               );

            case 'database':
               $vars = $databasevar . '&subject=database';
               return array (
                  'schemas' => array (
                     'title' => $lang['strschemas'],
                     'url'   => "database.php?{$vars}",
                     'hide'  => (!$data->hasSchemas()),
                     'help'  => 'pg.schema',
                  ),
                  'sql' => array (
                     'title' => $lang['strsql'],
                     'url'   => "database.php?{$vars}&action=sql",
                     'help'  => 'pg.sql',
                  ),
                  'find' => array (
                     'title' => $lang['strfind'],
                     'url'   => "database.php?{$vars}&action=find",
                  ),
                  'variables' => array (
                     'title' => $lang['strvariables'],
                     'url'   => "database.php?{$vars}&action=variables",
                     'hide'  => (!$data->hasVariables()),
                     'help'  => 'pg.variable',
                  ),
                  'processes' => array (
                     'title' => $lang['strprocesses'],
                     'url'   => "database.php?{$vars}&action=processes",
                     'hide'  => (!$data->hasProcesses()),
                     'help'  => 'pg.process',
                  ),
                  'admin' => array (
                     'title' => $lang['stradmin'],
                     'url'   => "database.php?{$vars}&action=admin",
                  ),
                  'privileges' => array (
                     'title' => $lang['strprivileges'],
                     'url'   => "privileges.php?{$vars}",
                     'hide'  => (!isset($data->privlist['database'])),
                     'help'  => 'pg.privilege',
                  ),
                  'languages' => array (
                     'title' => $lang['strlanguages'],
                     'url'   => "languages.php?{$vars}",
                     'hide'  => $hide_advanced,
                     'help'  => 'pg.language',
                  ),
                  'casts' => array (
                     'title' => $lang['strcasts'],
                     'url'   => "casts.php?{$vars}",
                     'hide'  => ($hide_advanced || !$data->hasCasts()),
                     'help'  => 'pg.cast',
                  ),
                  'export' => array (
                     'title' => $lang['strexport'],
                     'url'   => "database.php?{$vars}&action=export",
                     'hide'  => (!$this->isDumpEnabled()),
                  ),
               );

            case 'schema':
               $vars = $databasevar . $schemavar . '&subject=schema';
               return array (
                  'tables' => array (
                     'title' => $lang['strtables'],
                     'url'   => "tables.php?{$vars}",
                     'help'  => 'pg.table',
                  ),
                  'views' => array (
                     'title' => $lang['strviews'],
                     'url'   => "views.php?{$vars}",
                     'help'  => 'pg.view',
                  ),
                  'sequences' => array (
                     'title' => $lang['strsequences'],
                     'url'   => "sequences.php?{$vars}",
                     'help'  => 'pg.sequence',
                  ),
                  'functions' => array (
                     'title' => $lang['strfunctions'],
                     'url'   => "functions.php?{$vars}",
                     'help'  => 'pg.function',
                  ),
                  'domains' => array (
                     'title' => $lang['strdomains'],
                     'url'   => "domains.php?{$vars}",
                     'hide'  => (!$data->hasDomains()),
                     'help'  => 'pg.domain',
                  ),
                  'aggregates' => array (
                     'title' => $lang['straggregates'],
                     'url'   => "aggregates.php?{$vars}",
                     'hide'  => $hide_advanced,
                     'help'  => 'pg.aggregate',
                  ),
                  'types' => array (
                     'title' => $lang['strtypes'],
                     'url'   => "types.php?{$vars}",
                     'hide'  => $hide_advanced,
                     'help'  => 'pg.type',
                  ),
                  'operators' => array (
                     'title' => $lang['stroperators'],
                     'url'   => "operators.php?{$vars}",
                     'hide'  => $hide_advanced,
                     'help'  => 'pg.operator',
                  ),
                  'opclasses' => array (
                     'title' => $lang['stropclasses'],
                     'url'   => "opclasses.php?{$vars}",
                     'hide'  => $hide_advanced,
                     'help'  => 'pg.opclass',
                  ),
                  'conversions' => array (
                     'title' => $lang['strconversions'],
                     'url'   => "conversions.php?{$vars}",
                     'hide'  => ($hide_advanced || !$data->hasConversions()),
                     'help'  => 'pg.conversion',
                  ),
                  'privileges' => array (
                     'title' => $lang['strprivileges'],
                     'url'   => "privileges.php?{$vars}",
                     'hide'  => (!$data->hasSchemas()),
                     'help'  => 'pg.privilege',
                  ),
               );

            case 'table':
               $table = urlencode($_REQUEST['table']);
               $vars = $databasevar . $schemavar . "&table={$table}&subject=table";
               return array (
                  'columns' => array (
                     'title' => $lang['strcolumns'],
                     'url'   => "tblproperties.php?{$vars}",
                  ),
                  'indexes' => array (
                     'title' => $lang['strindexes'],
                     'url'   => "indexes.php?{$vars}",
                     'help'  => 'pg.index',
                  ),
                  'constraints' => array (
                     'title' => $lang['strconstraints'],
                     'url'   => "constraints.php?{$vars}",
                     'help'  => 'pg.constraint',
                  ),
                  'triggers' => array (
                     'title' => $lang['strtriggers'],
                     'url'   => "triggers.php?{$vars}",
                     'help'  => 'pg.trigger',
                  ),
                  'rules' => array (
                     'title' => $lang['strrules'],
                     'url'   => "rules.php?{$vars}",
                     'help'  => 'pg.rule',
                  ),
                  'info' => array (
                     'title' => $lang['strinfo'],
                     'url'   => "info.php?{$vars}",
                  ),
                  'privileges' => array (
                     'title' => $lang['strprivileges'],
                     'url'   => "privileges.php?{$vars}",
                     'help'  => 'pg.privilege',
                  ),
                  'import' => array (
                     'title' => $lang['strimport'],
                     'url'   => "tblproperties.php?{$vars}&action=import",
                  ),
                  'export' => array (
                     'title' => $lang['strexport'],
                     'url'   => "tblproperties.php?{$vars}&action=export",
                  ),
               );
            
            case 'view':
               $view = urlencode($_REQUEST['view']);
               $vars = $databasevar . $schemavar . "&view={$view}&subject=view";
               return array (
                  'columns' => array (
                     'title' => $lang['strcolumns'],
                     'url'   => "viewproperties.php?{$vars}",
                  ),
                  'definition' => array (
                     'title' => $lang['strdefinition'],
                     'url'   => "viewproperties.php?{$vars}&action=definition",
                  ),
                  'rules' => array (
                     'title' => $lang['strrules'],
                     'url'   => "rules.php?{$vars}",
                     'help'  => 'pg.rule',
                  ),
                  'privileges' => array (
                     'title' => $lang['strprivileges'],
                     'url'   => "privileges.php?{$vars}",
                     'help'  => 'pg.privilege',
                  ),
                  'export' => array (
                     'title' => $lang['strexport'],
                     'url'   => "viewproperties.php?{$vars}&action=export",
                  ),
               );
            
            case 'function':
               $funcnam = urlencode($_REQUEST['function']);
               $funcoid = urlencode($_REQUEST['function_oid']);
               $vars = $databasevar . $schemavar . "&function={$funcnam}&function_oid={$funcoid}&subject=function";
               return array (
                  'definition' => array (
                     'title' => $lang['strdefinition'],
                     'url'   => "functions.php?{$vars}&action=properties",
                  ),
                  'privileges' => array (
                     'title' => $lang['strprivileges'],
                     'url'   => "privileges.php?{$vars}",
                  ),
               );
            
            case 'popup':
               $vars = $databasevar;
               return array (
                  'sql' => array (
                     'title' => $lang['strsql'],
                     'url'   => "sqledit.php?{$vars}&action=sql",
                     'help'  => 'pg.sql',
                  ),
                  'find' => array (
                     'title' => $lang['strfind'],
                     'url'   => "sqledit.php?{$vars}&action=find",
                  ),
               );
            
            default:
               return array();
         }
      }

      function getLastTabURL($section) {
         global $data;
         
         switch ($section) {
            case 'database':
            case 'schema':
               if ($data->hasSchemas() === false) {
                  $section = 'database';
                  $tabs = array_merge($this->getNavTabs('schema'), $this->getNavTabs('database'));
                  break;
               }
            default:
               $tabs = $this->getNavTabs($section);
         }
         
         if (isset($_SESSION['webdbLastTab'][$section]) && isset($tabs[$_SESSION['webdbLastTab'][$section]]))
            $tab = $tabs[$_SESSION['webdbLastTab'][$section]];
         else
            $tab = reset($tabs);
         
         return isset($tab['url']) ? $tab['url'] : null;
      }

      function printTrail($trail = array()) {
         global $lang;
         
         if (is_string($trail)) {
            $trail = $this->getTrail($trail);
         }
         
         echo "<div class=\"trail\"><table><tr>";
         
         foreach ($trail as $crumb) {
            echo "<td>";
            $crumblink = "<a";
            
            if (isset($crumb['url']))
               $crumblink .= ' href="' . $this->printVal($crumb['url'], 'nbsp') . '"';
            
            if (isset($crumb['title']))
               $crumblink .= " title=\"{$crumb['title']}\"";
            
            $crumblink .= ">" . htmlspecialchars($crumb['text']) . "</a>";
            
            if (isset($crumb['help']))
               $this->printHelp($crumblink, $crumb['help']);
            else
               echo $crumblink;
            
            echo "{$lang['strseparator']}";
            echo "</td>";
         }
         
         echo "</tr></table></div>\n";
      }

      function getTrail($subject = null) {
         global $lang, $conf;
         
         $trail = array();
         $vars = '';
         $done = false;
         
         $trail['server'] = array(
            'title' => $lang['strserver'],
            'text'  => $conf['servers'][$_SESSION['webdbServerID']]['desc'],
            'url'   => 'redirect.php?section=server',
            'help'  => 'pg.server'
         );
         if ($subject == 'server') $done = true;
         
         if (isset($_REQUEST['database']) && !$done) {
            $vars = 'database='.urlencode($_REQUEST['database']).'&';
            $trail['database'] = array(
               'title' => $lang['strdatabase'],
               'text'  => $_REQUEST['database'],
               'url'   => "redirect.php?section=database&{$vars}",
               'help'  => 'pg.database'
            );
         }
         if ($subject == 'database') $done = true;
         
         if (isset($_REQUEST['schema']) && !$done) {
            $vars .= 'schema='.urlencode($_REQUEST['schema']).'&';
            $trail['schema'] = array(
               'title' => $lang['strschema'],
               'text'  => $_REQUEST['schema'],
               'url'   => "redirect.php?section=schema&{$vars}",
               'help'  => 'pg.schema'
            );
         }
         if ($subject == 'schema') $done = true;
         
         if (isset($_REQUEST['table']) && !$done) {
            $vars .= "section=table&table=".urlencode($_REQUEST['table']);
            $trail['table'] = array(
               'title' => $lang['strtable'],
               'text'  => $_REQUEST['table'],
               'url'   => "redirect.php?{$vars}",
               'help'  => 'pg.table'
            );
         } elseif (isset($_REQUEST['view']) && !$done) {
            $vars .= "section=view&view=".urlencode($_REQUEST['view']);
            $trail['view'] = array(
               'title' => $lang['strview'],
               'text'  => $_REQUEST['view'],
               'url'   => "redirect.php?{$vars}",
               'help'  => 'pg.view'
            );
         }
         if ($subject == 'table' || $subject == 'view') $done = true;
         
         if (!$done && !is_null($subject)) {
            switch ($subject) {
               case 'function':
                  $vars .= "{$subject}_oid=".urlencode($_REQUEST[$subject.'_oid']).'&';
                  $vars .= "section={$subject}&{$subject}=".urlencode($_REQUEST[$subject]);
                  $trail[$subject] = array(
                     'title' => $lang['str'.$subject],
                     'text'  => $_REQUEST[$subject],
                     'url'   => "redirect.php?{$vars}",
                     'help'  => 'pg.function'
                  );
                  break;
               default:
                  if (isset($_REQUEST[$subject])) {
                     $trail[$_REQUEST[$subject]] = array(
                        'title' => $lang['str'.$subject],
                        'text'  => $_REQUEST[$subject],
                        'help'  => 'pg.'.$subject   
                     );
                  }
            }
         }
         
         return $trail;
      }

      function printPages($page, $pages, $url, $max_width = 20) {
         global $lang;

         $window = 10;

         if ($page < 0 || $page > $pages) return;
         if ($pages < 0) return;
         if ($max_width <= 0) return;

         if ($pages > 1) {
            echo "<center><p>\n";
            if ($page != 1) {
               $temp = str_replace('%s', 1, $url);
               echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strfirst']}</a>\n";
               $temp = str_replace('%s', $page - 1, $url);
               echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strprev']}</a>\n";
            }
            
            if ($page <= $window) { 
               $min_page = 1; 
               $max_page = min(2 * $window, $pages); 
            }
            elseif ($page > $window && $pages >= $page + $window) { 
               $min_page = ($page - $window) + 1; 
               $max_page = $page + $window; 
            }
            else { 
               $min_page = ($page - (2 * $window - ($pages - $page))) + 1; 
               $max_page = $pages; 
            }
            
            $min_page = max($min_page, 1);
            $max_page = min($max_page, $pages);
            
            for ($i = $min_page; $i <= $max_page; $i++) {
               $temp = str_replace('%s', $i, $url);
               if ($i != $page) echo "<a class=\"pagenav\" href=\"{$temp}\">$i</a>\n";
               else echo "$i\n";
            }
            if ($page != $pages) {
               $temp = str_replace('%s', $page + 1, $url);
               echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strnext']}</a>\n";
               $temp = str_replace('%s', $pages, $url);
               echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strlast']}</a>\n";
            }
            echo "</p></center>\n";
         }
      }      

      function printHelp($str, $help) {
         global $lang, $data;
         
         echo $str;
         if ($help) {
            echo "<a class=\"help\" href=\"";
            echo htmlspecialchars("help.php?help=".urlencode($help));
            echo "\" title=\"{$lang['strhelp']}\" target=\"phppgadminhelp\">{$lang['strhelpicon']}</a>";
         }
      }
   
      function setFocus($object) {
         echo "<script language=\"JavaScript\">\n";
         echo "<!--\n";
         echo "   document.{$object}.focus();\n";
         echo "-->\n";
         echo "</script>\n";
      }

      function inisizeToBytes($strIniSize) {
         $a_IniParts = array();
         if (!is_string($strIniSize)) return false;

         if (!preg_match ('/^(\d+)([bkm]*)$/i', $strIniSize,$a_IniParts)) return false;

         $nSize = (double) $a_IniParts[1];
         $strUnit = strtolower($a_IniParts[2]);

         switch($strUnit) {
            case 'm':
               return ($nSize * (double) 1048576);
            case 'k':
               return ($nSize * (double) 1024);
            case 'b':
            default:
               return $nSize;
         }
           }       

      function printUrlVars(&$vars, &$fields) {
         foreach ($vars as $var => $varfield) {
            echo "{$var}=", urlencode($fields[$varfield]), "&amp;";
         }
      }
      
      function printTable(&$tabledata, &$columns, &$actions, $nodata = null, $pre_fn = null) {
         global $data, $conf, $misc;
         global $PHP_SELF;

         if ($tabledata->recordCount() > 0) {
            
            if (!$conf['show_comments']) {
               unset($columns['comment']);
            }

            if (isset($actions['properties'])) {
               reset($columns);
               $first_column = key($columns);
               $columns[$first_column]['url'] = $actions['properties']['url'];
               $columns[$first_column]['vars'] = $actions['properties']['vars'];
               unset($actions['properties']);
            }
            
            if (isset($columns['comment'])) {
            }
            
            echo "<table>\n";
            echo "<tr>\n";
            foreach ($columns as $column_id => $column) {
               switch ($column_id) {
                  case 'actions':
                     echo "<th class=\"data\" colspan=\"", count($actions), "\">{$column['title']}</th>\n";
                     break;
                  default:
                     echo "<th class=\"data\">";
                     if (isset($column['help']))
                        $this->printHelp($column['title'], $column['help']);
                     else
                        echo $column['title'];
                     echo "</th>\n";
                     break;
               }
            }
            echo "</tr>\n";
            
            $i = 0;
            while (!$tabledata->EOF) {
               $id = ($i % 2) + 1;
               
               unset($alt_actions);
               if (!is_null($pre_fn)) $alt_actions = $pre_fn($tabledata, $actions);
               if (!isset($alt_actions)) $alt_actions =& $actions;
               
               echo "<tr>\n";
               
               foreach ($columns as $column_id => $column) {
                  if (isset($column['url']) && !isset($column['vars'])) $column['vars'] = array();
                  
                  switch ($column_id) {
                     case 'actions':
                        foreach ($alt_actions as $action) {
                           if (isset($action['disable'])) {
                              echo "<td class=\"data{$id}\"></td>";
                           } else {
                              echo "<td class=\"opbutton{$id}\">";
                              echo "<a href=\"{$action['url']}";
                              $misc->printUrlVars($action['vars'], $tabledata->f);
                              echo "\">{$action['title']}</a></td>";
                           }
                        }
                        break;
                     default;
                        echo "<td class=\"data{$id}\">";
                        if (isset($column['url'])) {
                           echo "<a href=\"{$column['url']}";
                           $misc->printUrlVars($column['vars'], $tabledata->f);
                           echo "\">";
                        }
                        
                        $type = isset($column['type']) ? $column['type'] : null;
                        $params = isset($column['params']) ? $column['params'] : array();
                        echo $misc->printVal($tabledata->f[$column['field']], $type, $params);
                        
                        if (isset($column['url'])) echo "</a>";

                        echo "</td>\n";
                        break;
                  }
               }
               echo "</tr>\n";
               
               $tabledata->moveNext();
               $i++;
            }
            
            echo "</table>\n";
            
            return true;
         } else {
            if (!is_null($nodata)) {
               echo "<p>{$nodata}</p>\n";
            }
            return false;
         }
      }

      function amt2word ( $amt, $cur="Rupee", $dcur="Paise", $cpos=1) {
         if ($amt == "0" ) return "Zero $cur" ;
         $wrd = ($amt < 0 )  ? "Minus " : "" ;
         list($rupee,$paise) = explode(".",number_format($amt,2, '.', ''));
         if ( $cur==null) {
             $dcur="point" ;
             $cpos = 0 ;
         }
         if (abs($rupee) > 0 ) $cur = $cur . "s" ;
         if ($paise > 0 ) $dcur = $dcur . "s" ; 
         if ($rupee == 0 )  $wrd .= ( $cur==null) ? "Zero " :  "" ;
         else $wrd .= ($cpos == 0) ? "$cur " . num2word ( abs($rupee)) : num2word ( abs($rupee)) . "$cur "  ;
         if ($paise > 0) {
            $wrd .= (($wrd == "") || ($cur==null)) ?  "" : "and " ;
            $wrd .= ($cpos == 0) ? "$dcur " . num2word ( $paise) : num2word ( $paise) . "$dcur"  ;
         }
          return $wrd ;
      }
      
      function num2word ( $num) {
         $a20 = array('','One ','Two ','Three ','Four ','Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen ');
         $a10 = array('','Ten ','Twenty ','Thirty ','Forty ','Fifty ','Sixty ','Seventy ','Eighty ','Ninety ');
         $crs =  (int)($num / 10000000) ;
         $lakhs = (int)(($num % 10000000) / 100000) ;
         $x1000 = (int)(($num % 100000) / 1000) ;
         $x100 = (int)(($num % 1000) / 100) ;
         $x10 = (int)(($num % 100) / 10) ;
         $r20 = $num % 20 ;
         $r10 = $num % 10 ;
         if ( ($num % 10000000) == 0 ) return $wrd . ($crs > 1) ? num2word($crs) . "Crores " : num2word($crs) . "Crore " ;
         $wrd = ($crs != 0 ) ? num2word($crs) . "Crore " : "" ;
         if ( ($num % 100000) == 0 ) return $wrd . ($lakhs > 1) ? num2word($lakhs) . "Lakhs " : num2word($lakhs) . "Lakh " ;
         $wrd .= ($lakhs != 0 ) ? num2word($lakhs) . "Lakh ": "" ;
         $wrd .= ($x1000 != 0 ) ? num2word($x1000) . "Thousand " : "" ;
         $wrd .= ($x100 != 0 ) ? num2word($x100) . "Hundred " : "" ;
         if ( ($num > 100 ) && (($num % 100) != 0 )) $wrd .= "and " ;
         if ( $x10 < 2 ) return $wrd . $a20[$r20] ;
         return  $wrd . $a10[$x10]  . $a20[$r10] ;
     }
     function no_to_words($no) {
        $words = array('0'=> '' ,'1'=> 'One' ,'2'=> 'Two' ,'3' => 'Three','4' => 'Four','5' => 'Five','6' => 'Six','7' => 'Seven','8' => 'Eight','9' => 'Nine','10' => 'Ten','11' => 'Eleven','12' => 'Twelve','13' => 'Thirteen','14' => 'Fouteen','15' => 'Fifteen','16' => 'Sixteen','17' => 'Seventeen','18' => 'Eighteen','19' => 'Nineteen','20' => 'Twenty','30' => 'Thirty','40' => 'Fourty','50' => 'Fifty','60' => 'Sixty','70' => 'Seventy','80' => 'Eighty','90' => 'Ninty','100' => 'Hundred &','1000' => 'Thousand','100000' => 'Lakh','10000000' => 'Crore');
        if($no == 0) {
           return ' ';
        } else {
           $novalue='';
           $highno=$no;
           $remainno=0;
           $value=100;
           $value1=1000;
           while($no>=100) {
              if(($value <= $no) &&($no < $value1)) {
                 $novalue=$words["$value"];
                 $highno = (int)($no/$value);
                 $remainno = $no % $value;
                 break;
              }
              $value= $value1;
              $value1 = $value * 100;
           }
           if(array_key_exists("$highno",$words)) {
              return $words["$highno"]." ".$novalue." ".$this->no_to_words($remainno);
           } else {
              $unit=$highno%10;
              $ten =(int)($highno/10)*10;
              return $words["$ten"]." ".$words["$unit"]." ".$novalue." ".$this->no_to_words($remainno);
           }
        }
      }
  }
?>
