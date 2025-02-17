<?php
   include_once('classes/db/OSS_base.php');
   
   class BaseDB extends OSS_base {
   
      function BaseDB($type) {
         $this->OSS_base($type);
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
         
      function hasTables() { return false; }
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
