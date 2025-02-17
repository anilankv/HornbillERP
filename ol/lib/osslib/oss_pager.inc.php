<?php
   class OSS_Pager {
      var $id;    // unique id for pager (defaults to 'oss')
      var $db;    // OSS connection object
      var $sql;    // sql used
      var $rs;   // recordset generated
      var $curr_page;   // current page number before Render() called, calculated in constructor
      var $rows;      // number of rows per page
      var $linksPerPage=10; // number of links per page in navigation bar
      var $showPageLinks; 
   
      var $gridAttributes = 'width=100% border=1 bgcolor=white';
      
      var $first = '<code>|&lt;</code>';
      var $prev = '<code>&lt;&lt;</code>';
      var $next = '<code>>></code>';
      var $last = '<code>>|</code>';
      var $moreLinks = '...';
      var $startLinks = '...';
      var $gridHeader = false;
      var $htmlSpecialChars = true;
      var $page = 'Page';
      var $linkSelectedColor = 'red';
      var $cache = 0;  #secs to cache with CachePageExecute()
      
      function OSS_Pager(&$db,$sql,$id = 'oss', $showPageLinks = false) {
         global $HTTP_SERVER_VARS,$PHP_SELF,$HTTP_SESSION_VARS,$HTTP_GET_VARS;
      
         $curr_page = $id.'_curr_page';
         if (empty($PHP_SELF)) $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];
         
         $this->sql = $sql;
         $this->id = $id;
         $this->db = $db;
         $this->showPageLinks = $showPageLinks;
         
         $next_page = $id.'_next_page';   
         
         if (isset($HTTP_GET_VARS[$next_page])) {
            $HTTP_SESSION_VARS[$curr_page] = $HTTP_GET_VARS[$next_page];
         }
         if (empty($HTTP_SESSION_VARS[$curr_page])) $HTTP_SESSION_VARS[$curr_page] = 1; 
         
         $this->curr_page = $HTTP_SESSION_VARS[$curr_page];
         
      }
      
      function Render_First($anchor=true) {
         global $PHP_SELF;
         if ($anchor) {
            ?>
            <a href="<?php echo $PHP_SELF,'?',$this->id;?>_next_page=1"><?php echo $this->first;?></a> &nbsp; 
            <?php
         } else {
            print "$this->first &nbsp; ";
         }
      }
      
      function render_next($anchor=true) {
         global $PHP_SELF;
      
         if ($anchor) {
            ?>
            <a href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->AbsolutePage() + 1 ?>"><?php echo $this->next;?></a> &nbsp; 
            <?php
         } else {
            print "$this->next &nbsp; ";
         }
      }
      
      function render_last($anchor=true) {
         global $PHP_SELF;
      
         if (!$this->db->pageExecuteCountRows) return;
         
         if ($anchor) {
         ?>
            <a href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->LastPageNo() ?>"><?php echo $this->last;?></a> &nbsp; 
         <?php
         } else {
            print "$this->last &nbsp; ";
         }
      }
      
      function render_pagelinks() {
         global $PHP_SELF;
         $pages        = $this->rs->LastPageNo();
         $linksperpage = $this->linksPerPage ? $this->linksPerPage : $pages;
         for($i=1; $i <= $pages; $i+=$linksperpage) {
            if($this->rs->AbsolutePage() >= $i) {
               $start = $i;
            }
         }
         $numbers = '';
         $end = $start+$linksperpage-1;
         $link = $this->id . "_next_page";
         if($end > $pages) $end = $pages;
         if ($this->startLinks && $start > 1) {
            $pos = $start - 1;
            $numbers .= "<a href=$PHP_SELF?$link=$pos>$this->startLinks</a>  ";
         } 
         for($i=$start; $i <= $end; $i++) {
            if ($this->rs->AbsolutePage() == $i)
               $numbers .= "<font color=$this->linkSelectedColor><b>$i</b></font>  ";
            else $numbers .= "<a href=$PHP_SELF?$link=$i>$i</a>  ";
         }
         if ($this->moreLinks && $end < $pages) 
            $numbers .= "<a href=$PHP_SELF?$link=$i>$this->moreLinks</a>  ";
         print $numbers . ' &nbsp; ';
      }

      function render_prev($anchor=true) {
         global $PHP_SELF;
         if ($anchor) {
      ?>
         <a href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->AbsolutePage() - 1 ?>"><?php echo $this->prev;?></a> &nbsp; 
      <?php 
         } else {
            print "$this->prev &nbsp; ";
         }
      }
      
      function RenderGrid() {
         global $gSQLBlockRows; // used by rs2html to indicate how many rows to display
         include_once(OSS_DIR.'/rs2html.php');
         ob_start();
         $gSQLBlockRows = $this->rows;
         rs2html($this->rs,$this->gridAttributes,$this->gridHeader,$this->htmlSpecialChars);
         $s = ob_get_contents();
         ob_end_clean();
         return $s;
      }
      
      function RenderNav() {
         ob_start();
         if (!$this->rs->AtFirstPage()) {
            $this->Render_First();
            $this->Render_Prev();
         } else {
            $this->Render_First(false);
            $this->Render_Prev(false);
         }
           if ($this->showPageLinks){
               $this->Render_PageLinks();
           }
         if (!$this->rs->AtLastPage()) {
            $this->Render_Next();
            $this->Render_Last();
         } else {
            $this->Render_Next(false);
            $this->Render_Last(false);
         }
         $s = ob_get_contents();
         ob_end_clean();
         return $s;
      }
      
      function RenderPageCount() {
         if (!$this->db->pageExecuteCountRows) return '';
         $lastPage = $this->rs->LastPageNo();
         if ($lastPage == -1) $lastPage = 1; // check for empty rs.
         if ($this->curr_page > $lastPage) $this->curr_page = 1;
         return "<font size=-1>$this->page ".$this->curr_page."/".$lastPage."</font>";
      }
      
      function Render($rows=10) {
         global $OSS_COUNTRECS;
      
         $this->rows = $rows;
         
         $savec = $OSS_COUNTRECS;
         if ($this->db->pageExecuteCountRows) $OSS_COUNTRECS = true;
         if ($this->cache)
            $rs = &$this->db->CachePageExecute($this->cache,$this->sql,$rows,$this->curr_page);
         else
            $rs = &$this->db->PageExecute($this->sql,$rows,$this->curr_page);
         $OSS_COUNTRECS = $savec;
         
         $this->rs = &$rs;
         if (!$rs) {
            print "<h3>Query failed: $this->sql</h3>";
            return;
         }
         
         if (!$rs->EOF && (!$rs->AtFirstPage() || !$rs->AtLastPage())) 
            $header = $this->RenderNav();
         else
            $header = "&nbsp;";
         
         $grid = $this->RenderGrid();
         $footer = $this->RenderPageCount();
         $rs->Close();
         $this->rs = false;
         
         $this->RenderLayout($header,$grid,$footer);
      }
      
      function RenderLayout($header,$grid,$footer,$attributes='border=1 bgcolor=beige') {
         echo "<table ".$attributes."><tr><td>",
               $header,
            "</td></tr><tr><td>",
               $grid,
            "</td></tr><tr><td>",
               $footer,
            "</td></tr></table>";
      }
   }
?>
