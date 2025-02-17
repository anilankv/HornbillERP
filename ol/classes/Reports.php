<?php
   class Reports {
      var $driver;

      function Reports() {
         global $conf, $misc;
         $this->driver = &$misc->getDatabaseAccessor(
            $conf['servers'][$_SESSION['webdbServerID']]['host'],
            $conf['servers'][$_SESSION['webdbServerID']]['port'],
            'phppgadmin',
            $_SESSION['webdbUsername'],
            $_SESSION['webdbPassword']);
      }

      function &getReports() {
         global $conf;
         if ($conf['owned_reports_only']) {
            $filter['created_by'] = $_SESSION['webdbUsername'];
         }
         else $filter = array();

         $sql = $this->driver->getSelectSQL('ppa_reports',
            array('report_id', 'report_name', 'db_name', 'date_created', 'created_by', 'descr', 'report_sql'),
            $filter, array(), array('report_name'));

         return $this->driver->selectSet($sql);
      }

      function &getReport($report_id) {
         $sql = $this->driver->getSelectSQL('ppa_reports',
            array('report_id', 'report_name', 'db_name', 'date_created', 'created_by', 'descr', 'report_sql'),
            array('report_id' => $report_id), array());

         return $this->driver->selectSet($sql);
      }
      
      function createReport($report_name, $db_name, $descr, $report_sql) {
         $temp = array(
            'report_name' => $report_name,
            'db_name' => $db_name,
            'created_by' => $_SESSION['webdbUsername'],
            'report_sql' => $report_sql
         );
         if ($descr != '') $temp['descr'] = $descr;

         return $this->driver->insert('ppa_reports', $temp);
      }

      function alterReport($report_id, $report_name, $db_name, $descr, $report_sql) {
         $temp = array(
            'report_name' => $report_name,
            'db_name' => $db_name,
            'created_by' => $_SESSION['webdbUsername'],
            'report_sql' => $report_sql
         );
         if ($descr != '') $temp['descr'] = $descr;

         return $this->driver->update('ppa_reports', $temp,
                     array('report_id' => $report_id));
      }
      function dropReport($report_id) {
         return $this->driver->delete('ppa_reports', array('report_id' => $report_id));
      }

   }
?>
