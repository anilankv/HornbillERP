<?php
   include_once('classes/db/OSS_base.php');
   class MySQL extends OSS_base {
   
      var $dbFields = array('dbname' => 'Database');
      var $tbFields = array('tbname' => 'Name', 'tbowner' => '');
      var $id = '';
      function MySQL($host, $port, $database, $user, $password) {
         $this->OSS_base('mysql');
         $myhost = "{$host}:{$port}";
         if ($database === null) $database = 'mysql';
         $this->conn->connect($myhost, $user, $password, $database);
      }
   
      function deleteRow($table, $key) {
         if (!is_array($key)) return -1;
         else return $this->delete($table, $key);
      }
      
      function editRow($table, $vars, $nulls, $format, $types, $keyarr) {
         if (!is_array($vars) || !is_array($nulls) || !is_array($format) || !is_array($types)) {
            return -1;
         } else { 
            $this->fieldClean($table);
            if (sizeof($vars) > 0) {
               foreach($vars as $key => $value) {
                  $this->fieldClean($key);
   
                  if (isset($nulls[$key])) $tmp = 'NULL';
                  else $tmp = $this->formatValue($types[$key], $format[$key], $value);
                  if (isset($sql)) $sql .= ", \"{$key}\"={$tmp}";
                  else $sql = "UPDATE \"{$table}\" SET \"{$key}\"={$tmp}";
               }
               $first = true;
               foreach ($keyarr as $k => $v) {
                  $this->fieldClean($k);
                  $this->clean($v);
                  if ($first) {
                     $sql .= " WHERE \"{$k}\"='{$v}'";
                     $first = false;
                  } else $sql .= " AND \"{$k}\"='{$v}'";
               }            
            }         
            return $this->execute($sql);
         }
      }
   
      function insertRow($table, $vars, $nulls, $format, $types) {
         if (!is_array($vars) || !is_array($nulls) || !is_array($format) || !is_array($types)) {
            return -1;
        } else {
            $this->fieldClean($table);
   
            if (sizeof($vars) > 0) {
               $fields = '';
               $values = '';
               foreach($vars as $key => $value) {
                  $this->fieldClean($key);
      
                  if (isset($nulls[$key])) $tmp = 'NULL';
                  else $tmp = $this->formatValue($types[$key], $format[$key], $value);
                  
                  if ($fields) $fields .= ", \"{$key}\"";
                  else $fields = "INSERT INTO \"{$table}\" (\"{$key}\"";
   
                  if ($values) $values .= ", {$tmp}";
                  else $values = ") VALUES ({$tmp}";
               }
               $sql = $fields . $values . ')';
            }         
            return $this->execute($sql);
         }
      }
      
      function getSelectSQL($table, $show, $values, $nulls, $orderby = array()) {
         $this->fieldClean($table);
   
         $sql = "SELECT \"" . join('","', $show) . "\" FROM \"{$table}\"";
   
         $first = true;
         if (is_array($values) && sizeof($values) > 0) {
            foreach ($values as $k => $v) {
               if ($v != '' && !in_array($k, $nulls)) {
                  if ($first) {
                     $this->fieldClean($k);
                     $this->clean($v);
                     // @@ FIX THIS QUOTING
                     $sql .= " WHERE \"{$k}\"='{$v}'";
                     $first = false;
                  } else {
                     $sql .= " AND \"{$k}\"='{$v}'";
                  }
               }
            }
         }
   
         if (is_array($nulls) && sizeof($nulls) > 0) {
            foreach ($nulls as $v) {
               if ($first) {
                  $this->fieldClean($k);
                  $sql .= " WHERE \"{$k}\" IS NULL";
                  $first = false;
               } else {
                  $sql .= " AND \"{$k}\" IS NULL";
               }
            }
         }
   
         if (is_array($orderby) && sizeof($orderby) > 0) {
            $sql .= " ORDER BY \"" . join('","', $orderby) . "\"";
         }
   
         return $sql;
      }
   
      function &dumpRelation($relation, $oids) {
         $this->fieldClean($relation);
         if ($oids) $oid_str = $this->id . ', ';
         else $oid_str = '';
         return $this->selectSet("SELECT {$oid_str}* FROM \"{$relation}\"");
      }
         
      function fieldClean(&$str) {
         return $str;
      }
   
      function &getDatabases() {
         $sql = "SHOW DATABASES";
         return $this->selectSet($sql);
      }
   
      function &getDatabase($database) {
         $this->clean($database);
         $sql = "SHOW DATABASES LIKE '{$database}'";
         return $this->selectRow($sql);
      }
   
      function dropDatabase($database) {
         $this->clean($database);
         $sql = "DROP DATABASE {$database}";
      }
   
      function &getTables() {
         $sql = "SHOW TABLE STATUS";
         return $this->selectSet($sql);
      }
   
      function &getTableByName($table) {
         $this->clean($table);
         $sql = "SHOW TABLE STATUS LIKE '{$table}'";
         return $this->selectRow($sql);
      }
   
      function dropTable($table) {
         $this->clean($table);
         $sql = "DROP TABLE {$table}";
         return $this->execute($sql);
      }
   
      function renameTable($table, $newName) {
         $this->clean($table);
         $this->clean($newName);
         $sql = "ALTER TABLE {$table} RENAME {$newName}";
         return $this->execute($sql);
      }
   
      function addUniqueConstraint($table, $fields, $name = '') {
         $this->clean($table);
         $this->arrayClean($fields);
         $this->clean($name);
         if ($name != '')
            $sql = "ALTER TABLE {$table} ADD UNIQUE {$name} (\"" . join('","', $fields) . "\")";
         else
            $sql = "ALTER TABLE {$table} ADD UNIQUE (\"" . join('","', $fields) . "\")";
         return $this->execute($sql);
      }
   
      function dropUniqueConstraint($table, $name) {
         $this->clean($table);
         $this->clean($name);
         $sql = "ALTER TABLE {$table} DROP INDEX {$name}";
         return $this->execute($sql);
      }   
       
      function addPrimaryKeyConstraint($table, $fields, $name = '') {
         $this->clean($table);
         $this->arrayClean($fields);
         $this->clean($name);
         if ($name != '') return -99;
         else $sql = "ALTER TABLE {$table} ADD PRIMARY KEY (\"" . join('","', $fields) . "\")";
         return $this->execute($sql);
      }
   
      function dropPrimaryKeyConstraint($table, $name = '') {
         $this->clean($table);
         $this->clean($name);
         $sql = "ALTER TABLE {$table} DROP PRIMARY KEY";
         return $this->execute($sql);
      }   
   
      function addColumnToTable($table, $column, $type, $size = '') {
         $this->clean($table);
         $this->clean($column);
         $this->clean($type);
         $this->clean($size);
         if ($size == '') $sql = "ALTER TABLE {$table} ADD COLUMN {$column} {$type}";
         else $sql = "ALTER TABLE {$table} ADD COLUMN {$column} {$type}({$size})";
         return $this->execute($sql);
      }
   
      function dropColumnFromTable($table, $column) {
         $this->clean($table);
         $this->clean($column);
         $sql = "ALTER TABLE {$table} DROP COLUMN {$column}";
         return $this->execute($sql);
      }
   
      function setColumnDefault($table, $column, $default) {
         $this->clean($table);
         $this->clean($column);
         $sql = "ALTER TABLE {$table} ALTER COLUMN {$column} SET DEFAULT {$default}";
         return $this->execute($sql);
      }
   
      function dropColumnDefault($table, $column) {
         $this->clean($table);
         $this->clean($column);
         $sql = "ALTER TABLE {$table} ALTER COLUMN {$column} DROP DEFAULT";
         return $this->execute($sql);
      }
   
      function setColumnNull($table, $column, $state) {
         return -99;
      }
   
      function renameColumn($table, $column, $newName) {
         return -99;
      }
   
      function hasTables() { return true; }
      function hasViews() { return false; }
      function hasSequences() { return false; }
      function hasFunctions() { return false; }
      function hasTriggers() { return false; }
      function hasOperators() { return false; }
      function hasTypes() { return false; }
      function hasAggregates() { return false; }
      function hasRules() { return false; }
      function hasLanguages() { return false; }
      function hasSchemas() { return false; }
      function hasConversions() { return false; }
      function hasGrantOption() { return false; }
      function hasCluster() { return false; }
      function hasDropBehavior() { return false; }
      function hasSRFs() { return false; }
   
   }
?>
