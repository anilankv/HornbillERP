<?php
   include_once(dirname(__FILE__) . '/Postgres71.php');
   class Postgres72 extends Postgres71 {
      var $_lastSystemOID = 16554;
      var $privlist = array(
         'table' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'RULE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
         'view' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'RULE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
         'sequence' => array('SELECT', 'UPDATE', 'ALL PRIVILEGES')
      );
      var $extraTypes = array('SERIAL', 'BIGSERIAL');
   
      function Postgres72($conn) {
         $this->Postgres71($conn);
         $this->codemap['LATIN5'] = 'ISO-8859-9';
      }
   
      function &getProcesses($database = null) {
         if ($database === null)
            $sql = "SELECT * FROM pg_stat_activity ORDER BY datname, usename, procpid";
         else {
            $this->clean($database);
            $sql = "SELECT * FROM pg_stat_activity WHERE datname='{$database}' ORDER BY usename, procpid";
         }
         return $this->selectSet($sql);
      }
      
      function getChangeUserSQL($user) {
         $this->clean($user);
         return "SET SESSION AUTHORIZATION '{$user}';";
      }
   
      function hasObjectID($table) {
         $this->clean($table);
         $sql = "SELECT relhasoids FROM pg_class WHERE relname='{$table}'";
         $rs = $this->selectSet($sql);
         if ($rs->recordCount() != 1) return -99;
         else {
            $rs->f['relhasoids'] = $this->phpBool($rs->f['relhasoids']);
            return $rs->f['relhasoids'];
         }
      }
   
      function &getTable($table) {
         $this->clean($table);
         $sql = "SELECT pc.relname, 
            pg_get_userbyid(pc.relowner) AS relowner, 
            (SELECT description FROM pg_description pd 
                           WHERE pc.oid=pd.objoid AND objsubid = 0) AS relcomment 
            FROM pg_class pc WHERE pc.relname='{$table}'";
                        
         return $this->selectSet($sql);
      }
   
      function &getTables($all = false) {
         global $conf;
         if (!$conf['show_system'] || $all) $where = "AND c.relname NOT LIKE 'pg\\\\_%' ";
         else $where = '';
         
         $sql = "SELECT NULL AS nspname, c.relname, 
                  (SELECT usename FROM pg_user u WHERE u.usesysid=c.relowner) AS relowner, 
                  (SELECT description FROM pg_description pd WHERE c.oid=pd.objoid AND objsubid = 0) AS relcomment,
                  reltuples::integer
             FROM pg_class c WHERE c.relkind='r' {$where}ORDER BY relname";
         return $this->selectSet($sql);
      }
   
      function &getTableAttributes($table, $field = '') {
         $this->clean($table);
         $this->clean($field);
   
         if ($field == '') {      
            $sql = " SELECT a.attname, format_type(a.atttypid, a.atttypmod) as type, a.atttypmod,
                  a.attnotnull, a.atthasdef, adef.adsrc, -1 AS attstattarget, a.attstorage, 
                  t.typstorage, false AS attisserial, description as comment
               FROM pg_attribute a LEFT JOIN pg_attrdef adef
                  ON a.attrelid=adef.adrelid AND a.attnum=adef.adnum
                  LEFT JOIN pg_type t ON a.atttypid=t.oid
                  LEFT JOIN pg_description d ON (a.attrelid = d.objoid AND a.attnum = d.objsubid)
               WHERE 
                  a.attrelid = (SELECT oid FROM pg_class WHERE relname='{$table}') AND a.attnum > 0
               ORDER BY a.attnum";
         }
         else {
            $sql = " SELECT a.attname, format_type(a.atttypid, a.atttypmod) as type, 
                  format_type(a.atttypid, NULL) as base_type, a.atttypmod,
                  a.attnotnull, a.atthasdef, adef.adsrc,
                  -1 AS attstattarget, a.attstorage, t.typstorage, description as comment
               FROM pg_attribute a LEFT JOIN pg_attrdef adef
                  ON a.attrelid=adef.adrelid AND a.attnum=adef.adnum
                  LEFT JOIN pg_type t ON a.atttypid=t.oid
                  LEFT JOIN pg_description d ON (a.attrelid = d.objoid AND a.attnum = d.objsubid)
               WHERE a.attrelid = (SELECT oid FROM pg_class WHERE relname='{$table}') 
                  AND a.attname = '{$field}'";
         }
                  
         return $this->selectSet($sql);
      }
   
      function &getViews() {
         global $conf;
   
         if (!$conf['show_system'])
            $where = " WHERE viewname NOT LIKE 'pg\\\\_%'";
         else
            $where = '';
   
         $sql = "SELECT viewname AS relname, viewowner AS relowner, definition AS vwdefinition,
                  (SELECT description FROM pg_description pd, pg_class pc 
                   WHERE pc.oid=pd.objoid AND pc.relname=v.viewname AND pd.objsubid = 0) AS relcomment
            FROM pg_views v
            {$where}
            ORDER BY relname";
   
         return $this->selectSet($sql);
      }
      
      function &getView($view) {
         $this->clean($view);
         
         $sql = "SELECT viewname AS relname, viewowner AS relowner, definition AS vwdefinition,
              (SELECT description FROM pg_description pd, pg_class pc 
                WHERE pc.oid=pd.objoid AND pc.relname=v.viewname AND pd.objsubid = 0) AS relcomment
            FROM pg_views v
            WHERE viewname='{$view}'";
            
         return $this->selectSet($sql);
      }
      
      function dropConstraint($constraint, $relation, $type, $cascade) {
         $this->fieldClean($constraint);
         $this->fieldClean($relation);
   
         switch ($type) {
            case 'c':
               $sql = "ALTER TABLE \"{$relation}\" DROP CONSTRAINT \"{$constraint}\" RESTRICT";
               return $this->execute($sql);
               break;
            case 'p':
            case 'u':
               return $this->dropIndex($constraint, $cascade);
               break;
            case 'f':
               return -99;
         }            
      }
   
      function addUniqueKey($table, $fields, $name = '', $tablespace = '') {
         if (!is_array($fields) || sizeof($fields) == 0) return -1;
         $this->fieldClean($table);
         $this->fieldArrayClean($fields);
         $this->fieldClean($name);
         $this->fieldClean($tablespace);
   
         $sql = "ALTER TABLE \"{$table}\" ADD ";
         if ($name != '') $sql .= "CONSTRAINT \"{$name}\" ";
         $sql .= "UNIQUE (\"" . join('","', $fields) . "\")";
   
         if ($tablespace != '' && $this->hasTablespaces())
            $sql .= " USING INDEX TABLESPACE \"{$tablespace}\"";
   
         return $this->execute($sql);
      }
   
      function addPrimaryKey($table, $fields, $name = '', $tablespace = '') {
         if (!is_array($fields) || sizeof($fields) == 0) return -1;
         $this->fieldClean($table);
         $this->fieldArrayClean($fields);
         $this->fieldClean($name);
         $this->fieldClean($tablespace);
   
         $sql = "ALTER TABLE \"{$table}\" ADD ";
         if ($name != '') $sql .= "CONSTRAINT \"{$name}\" ";
         $sql .= "PRIMARY KEY (\"" . join('","', $fields) . "\")";
   
         if ($tablespace != '' && $this->hasTablespaces())
            $sql .= " USING INDEX TABLESPACE \"{$tablespace}\"";
         
         return $this->execute($sql);
      }
   
      function &getFunctions($all = false) {
         if ($all) $where = '';
         else $where = "AND p.oid > '{$this->_lastSystemOID}'";
   
         $sql = "SELECT p.oid AS prooid, p.proname, false AS proretset,
               format_type(p.prorettype, NULL) AS proresult,
               oidvectortypes(p.proargtypes) AS proarguments,
               (SELECT description FROM pg_description pd WHERE p.oid=pd.objoid) AS procomment
            FROM pg_proc p
            WHERE p.prorettype <> 0 AND (pronargs = 0 OR oidvectortypes(p.proargtypes) <> '')
               {$where}
            ORDER BY p.proname, proresult ";
   
         return $this->selectSet($sql);
      }
         
      function setFunction($function_oid, $funcname, $newname, $args, $returns, $definition, $language, $flags, $setof, $comment) {
         $status = $this->beginTransaction();
         if ($status != 0) {
            $this->rollbackTransaction();
            return -1;
         }
         if ($funcname != $newname) {
            $status = $this->dropFunction($function_oid, false);
            if ($status != 0) {
               $this->rollbackTransaction();
               return -2;
            }
            $status = $this->createFunction($newname, $args, $returns, $definition, $language, $flags, $setof, false);
            if ($status != 0) {
               $this->rollbackTransaction();
               return -3;
            }
         } else {
            $status = $this->createFunction($funcname, $args, $returns, $definition, $language, $flags, $setof, true);
            if ($status != 0) {
               $this->rollbackTransaction();
               return -3;
            }
         }
         $this->fieldClean($newname);
         $this->clean($comment);
         $status = $this->setComment('FUNCTION', "\"{$newname}\"({$args})", null, $comment);
         if ($status != 0) {
            $this->rollbackTransaction();
            return -4;
         }
         return $this->endTransaction();
      }
      function &getTypes($all = false, $tabletypes = false, $domains = false) {
         global $conf;
         if ($all || $conf['show_system']) {
            $where = '';
         } else {
            $where = "AND pt.oid > '{$this->_lastSystemOID}'::oid";
         }
         $where2 = "AND c.oid > '{$this->_lastSystemOID}'::oid";
         $tqry = "'c'";
         if ($tabletypes)
            $tqry .= ", 'r', 'v'";
         
         $sql = "SELECT pt.typname AS basename, format_type(pt.oid, NULL) AS typname,
               pu.usename AS typowner,
               (SELECT description FROM pg_description pd WHERE pt.oid=pd.objoid) AS typcomment
            FROM pg_type pt, pg_user pu
            WHERE pt.typowner = pu.usesysid
               AND (pt.typrelid = 0 OR (SELECT c.relkind IN ({$tqry}) FROM pg_class c WHERE c.oid = pt.typrelid {$where2}))
               AND typname !~ '^_' {$where} ORDER BY typname ";
   
         return $this->selectSet($sql);
      }
   
      function vacuumDB($table = '', $analyze = false, $full = false, $freeze = false) {
         $sql = "VACUUM";
         if ($full) $sql .= " FULL";
         if ($freeze) $sql .= " FREEZE";
         if ($analyze) $sql .= " ANALYZE";
         if ($table != '') {
            $this->fieldClean($table);
            $sql .= " \"{$table}\"";
         }
   
         return $this->execute($sql);
      }
   
      function analyzeDB($table = '') {
         if ($table != '') {
            $this->fieldClean($table);
            $sql = "ANALYZE \"{$table}\"";
         }
         else
            $sql = "ANALYZE";
   
         return $this->execute($sql);
      }
   
      function &getStatsDatabase($database) {
         $this->clean($database);
         $sql = "SELECT * FROM pg_stat_database WHERE datname='{$database}'";
         return $this->selectSet($sql);
      }
   
      function &getStatsTableTuples($table) {
         $this->clean($table);
   
         $sql = 'SELECT * FROM pg_stat_all_tables WHERE';
         if ($this->hasSchemas()) $sql .= " schemaname='{$this->_schema}' AND";
         $sql .= " relname='{$table}'";
   
         return $this->selectSet($sql);
      }
   
      function &getStatsTableIO($table) {
         $this->clean($table);
         $sql = 'SELECT * FROM pg_statio_all_tables WHERE';
         if ($this->hasSchemas()) $sql .= " schemaname='{$this->_schema}' AND";
         $sql .= " relname='{$table}'";
         return $this->selectSet($sql);
      }
   
      function &getStatsIndexTuples($table) {
         $this->clean($table);
         $sql = 'SELECT * FROM pg_stat_all_indexes WHERE';
         if ($this->hasSchemas()) $sql .= " schemaname='{$this->_schema}' AND";
         $sql .= " relname='{$table}' ORDER BY indexrelname";
         return $this->selectSet($sql);
      }
   
      function &getStatsIndexIO($table) {
         $this->clean($table);
   
         $sql = 'SELECT * FROM pg_statio_all_indexes WHERE';
         if ($this->hasSchemas()) $sql .= " schemaname='{$this->_schema}' AND";
         $sql .= " relname='{$table}' ORDER BY indexrelname";
   
         return $this->selectSet($sql);
      }
   
      function hasWithoutOIDs() { return true; }
      function hasPartialIndexes() { return true; }
      function hasProcesses() { return true; }
      function hasStatsCollector() { return true; }
      function hasFullVacuum() { return true; }
   }
?>
