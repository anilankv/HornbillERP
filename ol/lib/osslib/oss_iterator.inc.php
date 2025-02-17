<?php
   class OSS_Iterator implements Iterator {
      private $rs;
  
      function __construct($rs) {
          $this->rs = $rs;
      }

      function rewind() {
          $this->rs->MoveFirst();
      }
  
      function valid() {
          return !$this->rs->EOF;
      }
     
      function key() {
          return $this->rs->_currentRow;
      }
     
      function current() {
          return $this->rs->fields;
      }
     
      function next() {
          $this->rs->MoveNext();
      }
     
      function __call($func, $params) {
         return call_user_func_array(array($this->rs, $func), $params);
      }
   
      function hasMore() {
         return !$this->rs->EOF;
      }
  }
  
  class OSS_BASE_RS implements IteratorAggregate {
     public function getIterator() {
         return new OSS_Iterator($this);
     }
     function __toString()
     {
        include_once(OSS_DIR.'/export.php');
        return _oss_export($this,',',',',false,true);
     }
  } 
?>
