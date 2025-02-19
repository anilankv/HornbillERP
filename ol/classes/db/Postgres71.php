<?php
   include_once('./classes/db/Postgres.php');
   class Postgres71 extends Postgres {
      var $_lastSystemOID = 18539;
      var $_maxNameLen = 31;
      var $privlist = array(
         'table' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'RULE', 'ALL'),
         'view' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'RULE', 'ALL'),
         'sequence' => array('SELECT', 'UPDATE', 'ALL')
      );
      var $privmap = array(
         'r' => 'SELECT',
         'w' => 'UPDATE',
         'a' => 'INSERT',
         'd' => 'DELETE',
         'R' => 'RULE',
         'x' => 'REFERENCES',
         't' => 'TRIGGER',
         'X' => 'EXECUTE',
         'U' => 'USAGE',
         'C' => 'CREATE',
         'T' => 'TEMPORARY'
      );   
      var $funcprops = array(array('', 'ISSTRICT'), array('', 'ISCACHABLE'));
      var $defaultprops = array('', '');
      var $selectOps = array('=' => 'i', '!=' => 'i', '<' => 'i', '>' => 'i', '<=' => 'i', 
         '>=' => 'i', 'LIKE' => 'i', 'NOT LIKE' => 'i', 'ILIKE' => 'i', 'NOT ILIKE' => 'i', 
         '~' => 'i', '!~' => 'i', '~*' => 'i', '!~*' => 'i', 
         'IS NULL' => 'p', 'IS NOT NULL' => 'p', 'IN' => 'x', 'NOT IN' => 'x');
      var $joinOps = array('INNER JOIN' => 'INNER JOIN', 'LEFT JOIN' => 'LEFT JOIN', 'RIGHT JOIN' => 'RIGHT JOIN', 'FULL JOIN' => 'FULL JOIN');
   
      function Postgres71($conn) {
         $this->Postgres($conn);
      }
   
      function setClientEncoding($encoding) {
         $this->clean($encoding);
         $sql = "SET CLIENT_ENCODING TO '{$encoding}'";
         return $this->execute($sql);
      }
   
      function &getDatabases() {
         global $conf;
   
         if (isset($conf['owned_only']) && $conf['owned_only'] && !$this->isSuperUser($_SESSION['webdbUsername'])) {
            $username = $_SESSION['webdbUsername'];
            $this->clean($username);
            $clause = " AND pu.usename='{$username}'";
         }
         else $clause = '';
         
         if (!$conf['show_system']) $where = ' AND NOT pdb.datistemplate';
         else $where = ' AND pdb.datallowconn';
   
         $sql = "SELECT pdb.datname AS datname, pu.usename AS datowner, pg_encoding_to_char(encoding) AS datencoding,
                (SELECT description FROM pg_description pd WHERE pdb.oid=pd.objoid) AS datcomment
                FROM pg_database pdb, pg_user pu WHERE pdb.datdba = pu.usesysid {$where} {$clause}
            ORDER BY pdb.datname";
         return $this->selectSet($sql);
      }
   
      function browseQueryCount($query, $count) {
         return $this->selectField($count, 'total');
      }
         
      function &getTableAttributes($table, $field = '') {
         $this->clean($table);
         $this->clean($field);
         if ($field == '') {
            $sql = "SELECT
                  a.attname, t.typname as type, a.attlen, a.atttypmod, a.attnotnull, 
                  a.atthasdef, adef.adsrc, -1 AS attstattarget, a.attstorage, t.typstorage,
                  false AS attisserial, 
                  (SELECT description FROM pg_description d WHERE d.objoid = a.oid) as comment
               FROM
                  pg_attribute a LEFT JOIN pg_attrdef adef
                  ON a.attrelid=adef.adrelid AND a.attnum=adef.adnum,
                  pg_class c,
                  pg_type t
               WHERE
                  c.relname = '{$table}' AND a.attnum > 0 AND a.attrelid = c.oid AND a.atttypid = t.oid
               ORDER BY a.attnum";
         } else {
            $sql = "SELECT
                  a.attname, t.typname as type, t.typname as base_type,
                  a.attlen, a.atttypmod, a.attnotnull, 
                  a.atthasdef, adef.adsrc, -1 AS attstattarget, a.attstorage, t.typstorage, 
                  (SELECT description FROM pg_description d WHERE d.objoid = a.oid) as comment
               FROM
                  pg_attribute a LEFT JOIN pg_attrdef adef
                  ON a.attrelid=adef.adrelid AND a.attnum=adef.adnum,
                  pg_class c,
                  pg_type t
               WHERE
                  c.relname = '{$table}' AND a.attname='{$field}' AND a.attrelid = c.oid AND a.atttypid = t.oid";
         }
         return $this->selectSet($sql);
      }
   
      function formatType($typname, $typmod) {
         return $typname;
      }
         
      function &resetSequence($sequence) {
         $seq = &$this->getSequence($sequence);
         if ($seq->recordCount() != 1) return -1;
         $minvalue = $seq->f['min_value'];
         $this->fieldClean($sequence);
         $this->clean($sequence);
         $sql = "SELECT SETVAL('\"{$sequence}\"', {$minvalue}, FALSE)";
         return $this->execute($sql);
      }
   
      function getFunction($function_oid) {
         $this->clean($function_oid);
         
         $sql = "SELECT pc.oid AS prooid, proname, lanname as prolanguage,
                  format_type(prorettype, NULL) as proresult, prosrc, probin, proretset,
                  proisstrict, proiscachable, oidvectortypes(pc.proargtypes) AS proarguments,
                  (SELECT description FROM pg_description pd WHERE pc.oid=pd.objoid) AS procomment
               FROM pg_proc pc, pg_language pl
               WHERE pc.oid = '$function_oid'::oid AND pc.prolang = pl.oid ";
      
         return $this->selectSet($sql);
      }
   
      function getFunctionProperties($f) {
         $temp = array();
         $f['proisstrict'] = $this->phpBool($f['proisstrict']);
         if ($f['proisstrict']) $temp[] = 'ISSTRICT';
         else $temp[] = '';
         $f['proiscachable'] = $this->phpBool($f['proiscachable']);
         if ($f['proiscachable']) $temp[] = 'ISCACHABLE';
         else $temp[] = '';
         return $temp;
      }
      
      function &getConstraints($table) {
         $this->clean($table);
   
         $status = $this->beginTransaction();
         if ($status != 0) return -1;
   
         $sql = " SELECT conname, consrc, contype, indkey FROM (
               SELECT rcname AS conname, 'CHECK (' || rcsrc || ')' AS consrc,
                  'c' AS contype, rcrelid AS relid, NULL AS indkey
               FROM pg_relcheck UNION ALL 
               SELECT pc.relname, NULL, CASE WHEN indisprimary THEN 'p' ELSE 'u' END,
                  pi.indrelid, indkey
               FROM pg_class pc, pg_index pi
               WHERE pc.oid=pi.indexrelid AND (pi.indisunique OR pi.indisprimary)
            ) AS sub
            WHERE relid = (SELECT oid FROM pg_class WHERE relname='{$table}') ORDER BY 1 ";
   
         return $this->selectSet($sql);
      }   
      
      function &getTriggers($table = '') {
         $this->clean($table);
   
         $sql = "SELECT t.tgname, t.tgisconstraint, t.tgdeferrable, t.tginitdeferred, t.tgtype, 
            t.tgargs, t.tgnargs, t.tgconstrrelid, 
            (SELECT relname FROM pg_class c2 WHERE c2.oid=t.tgconstrrelid) AS tgconstrrelname,
            p.proname AS tgfname, c.relname, NULL AS tgdef
            FROM pg_trigger t LEFT JOIN pg_proc p ON t.tgfoid=p.oid, pg_class c
            WHERE t.tgrelid=c.oid AND c.relname='{$table}'";
   
         return $this->selectSet($sql);
      }   
   
      function &getAggregates() {
         global $conf;
         if ($conf['show_system']) $where = '';
         else $where = "WHERE a.oid > '{$this->_lastSystemOID}'::oid";
   
         $sql = " SELECT a.aggname AS proname, CASE a.aggbasetype WHEN 0 THEN NULL
                  ELSE format_type(a.aggbasetype, NULL) END AS proargtypes,
               (SELECT description FROM pg_description pd WHERE a.oid=pd.objoid) AS aggcomment
            FROM pg_aggregate a {$where} ORDER BY 1, 2; ";
         return $this->selectSet($sql);
      }
         
      function hasAlterTableOwner() { return true; }
      function hasFullSubqueries() { return true; }
   }
?>
