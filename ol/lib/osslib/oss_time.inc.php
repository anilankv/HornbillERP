<?php
   define('OSS_DATE_VERSION',0.15);
   
   if (!defined('OSS_ALLOW_NEGATIVE_TS')) define('OSS_NO_NEGATIVE_TS',1);
   
   function oss_date_test_date($y1,$m) {
      $t = oss_mktime(0,0,0,$m,13,$y1);
      if ("$y1-$m-13 00:00:00" != oss_date('Y-n-d H:i:s',$t)) {
         print "<b>$y1 error</b><br>";
         return false;
      }
      return true;
   }
   
   function oss_date_test() { 
      error_reporting(E_ALL);
      print "<h4>Testing oss_date and oss_mktime. version=".OSS_DATE_VERSION. "</h4>";
      @set_time_limit(0);
      $fail = false;
      
      if (!defined('OSS_TEST_DATES')) define('OSS_TEST_DATES',1);
      
      $t = oss_mktime(0,0,0);
      if (!(oss_date('Y-m-d') == date('Y-m-d'))) print 'Error in '.oss_mktime(0,0,0).'<br>';
      
      $t = oss_mktime(0,0,0,6,1,2102);
      if (!(oss_date('Y-m-d',$t) == '2102-06-01')) print 'Error in '.oss_date('Y-m-d',$t).'<br>';
      
      $t = oss_mktime(0,0,0,2,1,2102);
      if (!(oss_date('Y-m-d',$t) == '2102-02-01')) print 'Error in '.oss_date('Y-m-d',$t).'<br>';
      
      
      print "<p>Testing gregorian <=> julian conversion<p>";
      $t = oss_mktime(0,0,0,10,11,1492);
      if (!(oss_date('D Y-m-d',$t) == 'Fri 1492-10-11')) print 'Error in Columbus landing<br>';
      
      $t = oss_mktime(0,0,0,2,29,1500);
      if (!(oss_date('Y-m-d',$t) == '1500-02-29')) print 'Error in julian leap years<br>';
      
      $t = oss_mktime(0,0,0,2,29,1700);
      if (!(oss_date('Y-m-d',$t) == '1700-03-01')) print 'Error in gregorian leap years<br>';
      
      print  oss_mktime(0,0,0,10,4,1582).' ';
      print oss_mktime(0,0,0,10,15,1582);
      $diff = (oss_mktime(0,0,0,10,15,1582) - oss_mktime(0,0,0,10,4,1582));
      if ($diff != 3600*24) print " <b>Error in gregorian correction = ".($diff/3600/24)." days </b><br>";
         
      print " 15 Oct 1582, Fri=".(oss_dow(1582,10,15) == 5 ? 'Fri' : '<b>Error</b>')."<br>";
      print " 4 Oct 1582, Thu=".(oss_dow(1582,10,4) == 4 ? 'Thu' : '<b>Error</b>')."<br>";
      
      print "<p>Testing overflow<p>";
      
      $t = oss_mktime(0,0,0,3,33,1965);
      if (!(oss_date('Y-m-d',$t) == '1965-04-02')) print 'Error in day overflow 1 <br>';
      $t = oss_mktime(0,0,0,4,33,1971);
      if (!(oss_date('Y-m-d',$t) == '1971-05-03')) print 'Error in day overflow 2 <br>';
      $t = oss_mktime(0,0,0,1,60,1965);
      if (!(oss_date('Y-m-d',$t) == '1965-03-01')) print 'Error in day overflow 3 '.oss_date('Y-m-d',$t).' <br>';
      $t = oss_mktime(0,0,0,12,32,1965);
      if (!(oss_date('Y-m-d',$t) == '1966-01-01')) print 'Error in day overflow 4 '.oss_date('Y-m-d',$t).' <br>';
      $t = oss_mktime(0,0,0,12,63,1965);
      if (!(oss_date('Y-m-d',$t) == '1966-02-01')) print 'Error in day overflow 5 '.oss_date('Y-m-d',$t).' <br>';
      $t = oss_mktime(0,0,0,13,3,1965);
      if (!(oss_date('Y-m-d',$t) == '1966-01-03')) print 'Error in mth overflow 1 <br>';
      
      print "Testing 2-digit => 4-digit year conversion<p>";
      if (oss_year_digit_check(00) != 2000) print "Err 2-digit 2000<br>";
      if (oss_year_digit_check(10) != 2010) print "Err 2-digit 2010<br>";
      if (oss_year_digit_check(20) != 2020) print "Err 2-digit 2020<br>";
      if (oss_year_digit_check(30) != 2030) print "Err 2-digit 2030<br>";
      if (oss_year_digit_check(40) != 1940) print "Err 2-digit 1940<br>";
      if (oss_year_digit_check(50) != 1950) print "Err 2-digit 1950<br>";
      if (oss_year_digit_check(90) != 1990) print "Err 2-digit 1990<br>";
      
      print "<p>Testing date formating</p>";
      $fmt = '\d\a\t\e T Y-m-d H:i:s a A d D F g G h H i j l L m M n O \R\F\C822 r s t U w y Y z Z 2003';
      $s1 = date($fmt,0);
      $s2 = oss_date($fmt,0);
      if ($s1 != $s2) {
         print " date() 0 failed<br>$s1<br>$s2<br>";
      }
      flush();
      for ($i=100; --$i > 0; ) {
   
         $ts = 3600.0*((rand()%60000)+(rand()%60000))+(rand()%60000);
         $s1 = date($fmt,$ts);
         $s2 = oss_date($fmt,$ts);
         $pos = strcmp($s1,$s2);
   
         if (($s1) != ($s2)) {
            for ($j=0,$k=strlen($s1); $j < $k; $j++) {
               if ($s1[$j] != $s2[$j]) {
                  print substr($s1,$j).' ';
                  break;
               }
            }
            print "<b>Error date(): $ts<br><pre> 
   &nbsp; \"$s1\" (date len=".strlen($s1).")
   &nbsp; \"$s2\" (oss_date len=".strlen($s2).")</b></pre><br>";
            $fail = true;
         }
         
         $a1 = getdate($ts);
         $a2 = oss_getdate($ts);
         $rez = array_diff($a1,$a2);
         if (sizeof($rez)>0) {
            print "<b>Error getdate() $ts</b><br>";
               print_r($a1);
            print "<br>";
               print_r($a2);
            print "<p>";
            $fail = true;
         }
      }
      
      print "<p>Testing random dates between 100 and 4000</p>";
      oss_date_test_date(100,1);
      for ($i=100; --$i >= 0;) {
         $y1 = 100+rand(0,1970-100);
         $m = rand(1,12);
         oss_date_test_date($y1,$m);
         
         $y1 = 3000-rand(0,3000-1970);
         oss_date_test_date($y1,$m);
      }
      print '<p>';
      $start = 1960+rand(0,10);
      $yrs = 12;
      $i = 365.25*86400*($start-1970);
      $offset = 36000+rand(10000,60000);
      $max = 365*$yrs*86400;
      $lastyear = 0;
      
      print "Testing $start to ".($start+$yrs).", or $max seconds, offset=$offset: ";
      $cnt = 0;
      for ($max += $i; $i < $max; $i += $offset) {
         $ret = oss_date('m,d,Y,H,i,s',$i);
         $arr = explode(',',$ret);
         if ($lastyear != $arr[2]) {
            $lastyear = $arr[2];
            print " $lastyear ";
            flush();
         }
         $newi = oss_mktime($arr[3],$arr[4],$arr[5],$arr[0],$arr[1],$arr[2]);
         if ($i != $newi) {
            print "Error at $i, oss_mktime returned $newi ($ret)";
            $fail = true;
            break;
         }
         $cnt += 1;
      }
      echo "Tested $cnt dates<br>";
      if (!$fail) print "<p>Passed !</p>";
      else print "<p><b>Failed</b> :-(</p>";
   }
   
   function oss_dow($year, $month, $day) {
      if ($year <= 1582) {
         if ($year < 1582 || 
            ($year == 1582 && ($month < 10 || ($month == 10 && $day < 15)))) $greg_correction = 3;
          else
            $greg_correction = 0;
      } else
         $greg_correction = 0;
      
      if($month > 2)
          $month -= 2;
      else {
          $month += 10;
          $year--;
      }
      
      $day =  ( floor((13 * $month - 1) / 5) +
              $day + ($year % 100) +
              floor(($year % 100) / 4) +
              floor(($year / 100) / 4) - 2 *
              floor($year / 100) + 77);
      
      return (($day - 7 * floor($day / 7))) + $greg_correction;
   }
   
   function _oss_is_leap_year($year) {
      if ($year % 4 != 0) return false;
      
      if ($year % 400 == 0) {
         return true;
      } else if ($year > 1582 && $year % 100 == 0 ) {
         return false;
      } 
      
      return true;
   }
   
   function oss_is_leap_year($year) {
      return  _oss_is_leap_year(oss_year_digit_check($year));
   }
   
   function oss_year_digit_check($y) {
      if ($y < 100) {
      
         $yr = (integer) date("Y");
         $century = (integer) ($yr /100);
         
         if ($yr%100 > 50) {
            $c1 = $century + 1;
            $c0 = $century;
         } else {
            $c1 = $century;
            $c0 = $century - 1;
         }
         $c1 *= 100;
         if (($y + $c1) < $yr+30) $y = $y + $c1;
         else $y = $y + $c0*100;
      }
      return $y;
   }
   
   function oss_get_gmt_diff() {
   static $TZ;
      if (isset($TZ)) return $TZ;
      
      $TZ = mktime(0,0,0,1,2,1970,0) - gmmktime(0,0,0,1,2,1970,0);
      return $TZ;
   }
   
   function oss_getdate($d=false,$fast=false) {
      if ($d === false) return getdate();
      if (!defined('OSS_TEST_DATES')) {
         if ((abs($d) <= 0x7FFFFFFF)) { // check if number in 32-bit signed range
            if (!defined('OSS_NO_NEGATIVE_TS') || $d >= 0) // if windows, must be +ve integer
               return @getdate($d);
         }
      }
      return _oss_getdate($d);
   }
   
   function _oss_getdate($origd=false,$fast=false,$is_gmt=false) {
      $d =  $origd - ($is_gmt ? 0 : oss_get_gmt_diff());
      
      $_day_power = 86400;
      $_hour_power = 3600;
      $_min_power = 60;
      
      if ($d < -12219321600) $d -= 86400*10; // if 15 Oct 1582 or earlier, gregorian correction 
      
      $_month_table_normal = array("",31,28,31,30,31,30,31,31,30,31,30,31);
      $_month_table_leaf = array("",31,29,31,30,31,30,31,31,30,31,30,31);
      
      $d366 = $_day_power * 366;
      $d365 = $_day_power * 365;
      
      if ($d < 0) {
         $origd = $d;
         for ($a = 1970 ; --$a >= 0;) {
            $lastd = $d;
            
            if ($leaf = _oss_is_leap_year($a)) $d += $d366;
            else $d += $d365;
            
            if ($d >= 0) {
               $year = $a;
               break;
            }
         }
         
         $secsInYear = 86400 * ($leaf ? 366 : 365) + $lastd;
         
         $d = $lastd;
         $mtab = ($leaf) ? $_month_table_leaf : $_month_table_normal;
         for ($a = 13 ; --$a > 0;) {
            $lastd = $d;
            $d += $mtab[$a] * $_day_power;
            if ($d >= 0) {
               $month = $a;
               $ndays = $mtab[$a];
               break;
            }
         }
         
         $d = $lastd;
         $day = $ndays + ceil(($d+1) / ($_day_power));
   
         $d += ($ndays - $day+1)* $_day_power;
         $hour = floor($d/$_hour_power);
      
      } else {
         for ($a = 1970 ;; $a++) {
            $lastd = $d;
            
            if ($leaf = _oss_is_leap_year($a)) $d -= $d366;
            else $d -= $d365;
            if ($d < 0) {
               $year = $a;
               break;
            }
         }
         $secsInYear = $lastd;
         $d = $lastd;
         $mtab = ($leaf) ? $_month_table_leaf : $_month_table_normal;
         for ($a = 1 ; $a <= 12; $a++) {
            $lastd = $d;
            $d -= $mtab[$a] * $_day_power;
            if ($d < 0) {
               $month = $a;
               $ndays = $mtab[$a];
               break;
            }
         }
         $d = $lastd;
         $day = ceil(($d+1) / $_day_power);
         $d = $d - ($day-1) * $_day_power;
         $hour = floor($d /$_hour_power);
      }
      
      $d -= $hour * $_hour_power;
      $min = floor($d/$_min_power);
      $secs = $d - $min * $_min_power;
      if ($fast) {
         return array(
         'seconds' => $secs,
         'minutes' => $min,
         'hours' => $hour,
         'mday' => $day,
         'mon' => $month,
         'year' => $year,
         'yday' => floor($secsInYear/$_day_power),
         'leap' => $leaf,
         'ndays' => $ndays
         );
      }
      
      
      $dow = oss_dow($year,$month,$day);
   
      return array(
         'seconds' => $secs,
         'minutes' => $min,
         'hours' => $hour,
         'mday' => $day,
         'wday' => $dow,
         'mon' => $month,
         'year' => $year,
         'yday' => floor($secsInYear/$_day_power),
         'weekday' => gmdate('l',$_day_power*(3+$dow)),
         'month' => gmdate('F',mktime(0,0,0,$month,2,1971)),
         0 => $origd
      );
   }
   
   function oss_gmdate($fmt,$d=false) {
      return oss_date($fmt,$d,true);
   }
   
   function oss_date2($fmt, $d=false, $is_gmt=false) {
      if ($d !== false) {
         if (!preg_match( 
            "|^([0-9]{4})[-/\.]?([0-9]{1,2})[-/\.]?([0-9]{1,2})[ -]?(([0-9]{1,2}):?([0-9]{1,2}):?([0-9\.]{1,4}))?|", 
            ($d), $rr)) return oss_date($fmt,false,$is_gmt);
   
         if ($rr[1] <= 100 && $rr[2]<= 1) return oss_date($fmt,false,$is_gmt);
      
         if (!isset($rr[5])) $d = oss_mktime(0,0,0,$rr[2],$rr[3],$rr[1]);
         else $d = @oss_mktime($rr[5],$rr[6],$rr[7],$rr[2],$rr[3],$rr[1]);
      }
      
      return oss_date($fmt,$d,$is_gmt);
   }
   
   function oss_date($fmt,$d=false,$is_gmt=false) {
   static $daylight;
   
      if ($d === false) return ($is_gmt)? @gmdate($fmt): @date($fmt);
      if (!defined('OSS_TEST_DATES')) {
         if ((abs($d) <= 0x7FFFFFFF)) { // check if number in 32-bit signed range
            if (!defined('OSS_NO_NEGATIVE_TS') || $d >= 0) // if windows, must be +ve integer
               return ($is_gmt)? @gmdate($fmt,$d): @date($fmt,$d);
   
         }
      }
      $_day_power = 86400;
      
      $arr = _oss_getdate($d,true,$is_gmt);
      if (!isset($daylight)) $daylight = function_exists('oss_daylight_sv');
      if ($daylight) oss_daylight_sv($arr, $is_gmt);
      
      $year = $arr['year'];
      $month = $arr['mon'];
      $day = $arr['mday'];
      $hour = $arr['hours'];
      $min = $arr['minutes'];
      $secs = $arr['seconds'];
      
      $max = strlen($fmt);
      $dates = '';
      
      for ($i=0; $i < $max; $i++) {
         switch($fmt[$i]) {
         case 'T': $dates .= date('T');break;
         // YEAR
         case 'L': $dates .= $arr['leap'] ? '1' : '0'; break;
         case 'r': // Thu, 21 Dec 2000 16:01:07 +0200
         
            $dates .= gmdate('D',$_day_power*(3+oss_dow($year,$month,$day))).', '      
               . ($day<10?' '.$day:$day) . ' '.date('M',mktime(0,0,0,$month,2,1971)).' '.$year.' ';
            
            if ($hour < 10) $dates .= '0'.$hour; else $dates .= $hour; 
            
            if ($min < 10) $dates .= ':0'.$min; else $dates .= ':'.$min;
            
            if ($secs < 10) $dates .= ':0'.$secs; else $dates .= ':'.$secs;
            
            $gmt = oss_get_gmt_diff();
            $dates .= sprintf(' %s%04d',($gmt<0)?'+':'-',abs($gmt)/36); break;
               
         case 'Y': $dates .= $year; break;
         case 'y': $dates .= substr($year,strlen($year)-2,2); break;
         // MONTH
         case 'm': if ($month<10) $dates .= '0'.$month; else $dates .= $month; break;
         case 'Q': $dates .= ($month+3)>>2; break;
         case 'n': $dates .= $month; break;
         case 'M': $dates .= date('M',mktime(0,0,0,$month,2,1971)); break;
         case 'F': $dates .= date('F',mktime(0,0,0,$month,2,1971)); break;
         // DAY
         case 't': $dates .= $arr['ndays']; break;
         case 'z': $dates .= $arr['yday']; break;
         case 'w': $dates .= oss_dow($year,$month,$day); break;
         case 'l': $dates .= gmdate('l',$_day_power*(3+oss_dow($year,$month,$day))); break;
         case 'D': $dates .= gmdate('D',$_day_power*(3+oss_dow($year,$month,$day))); break;
         case 'j': $dates .= $day; break;
         case 'd': if ($day<10) $dates .= '0'.$day; else $dates .= $day; break;
         case 'S': 
            $d10 = $day % 10;
            if ($d10 == 1) $dates .= 'st';
            else if ($d10 == 2 && $day != 12) $dates .= 'nd';
            else if ($d10 == 3) $dates .= 'rd';
            else $dates .= 'th';
            break;
            
         // HOUR
         case 'Z':
            $dates .= ($is_gmt) ? 0 : -oss_get_gmt_diff(); break;
         case 'O': 
            $gmt = ($is_gmt) ? 0 : oss_get_gmt_diff();
            $dates .= sprintf('%s%04d',($gmt<0)?'+':'-',abs($gmt)/36); break;
            
         case 'H': 
            if ($hour < 10) $dates .= '0'.$hour; 
            else $dates .= $hour; 
            break;
         case 'h': 
            if ($hour > 12) $hh = $hour - 12; 
            else {
               if ($hour == 0) $hh = '12'; 
               else $hh = $hour;
            }
            
            if ($hh < 10) $dates .= '0'.$hh;
            else $dates .= $hh;
            break;
            
         case 'G': 
            $dates .= $hour;
            break;
            
         case 'g':
            if ($hour > 12) $hh = $hour - 12; 
            else {
               if ($hour == 0) $hh = '12'; 
               else $hh = $hour; 
            }
            $dates .= $hh;
            break;
         // MINUTES
         case 'i': if ($min < 10) $dates .= '0'.$min; else $dates .= $min; break;
         // SECONDS
         case 'U': $dates .= $d; break;
         case 's': if ($secs < 10) $dates .= '0'.$secs; else $dates .= $secs; break;
         // AM/PM
         // Note 00:00 to 11:59 is AM, while 12:00 to 23:59 is PM
         case 'a':
            if ($hour>=12) $dates .= 'pm';
            else $dates .= 'am';
            break;
         case 'A':
            if ($hour>=12) $dates .= 'PM';
            else $dates .= 'AM';
            break;
         default:
            $dates .= $fmt[$i]; break;
         // ESCAPE
         case "\\": 
            $i++;
            if ($i < $max) $dates .= $fmt[$i];
            break;
         }
      }
      return $dates;
   }
   
   function oss_gmmktime($hr,$min,$sec,$mon=false,$day=false,$year=false,$is_dst=false) {
      return oss_mktime($hr,$min,$sec,$mon,$day,$year,$is_dst,true);
   }
   
   function oss_mktime($hr,$min,$sec,$mon=false,$day=false,$year=false,$is_dst=false,$is_gmt=false) {
      if (!defined('OSS_TEST_DATES')) {
         if (1971 < $year && $year < 2038
            || $mon === false
            || !defined('OSS_NO_NEGATIVE_TS') && (1901 < $year && $year < 2038)
            )
               return $is_gmt?
                  @gmmktime($hr,$min,$sec,$mon,$day,$year):
                  @mktime($hr,$min,$sec,$mon,$day,$year);
      }
      
      $gmt_different = ($is_gmt) ? 0 : oss_get_gmt_diff();
      
      $hr = intval($hr);
      $min = intval($min);
      $sec = intval($sec);
      $mon = intval($mon);
      $day = intval($day);
      $year = intval($year);
      
      
      $year = oss_year_digit_check($year);
      
      if ($mon > 12) {
         $y = floor($mon / 12);
         $year += $y;
         $mon -= $y*12;
      }
      
      $_day_power = 86400;
      $_hour_power = 3600;
      $_min_power = 60;
      
      $_month_table_normal = array("",31,28,31,30,31,30,31,31,30,31,30,31);
      $_month_table_leaf = array("",31,29,31,30,31,30,31,31,30,31,30,31);
      
      $_total_date = 0;
      if ($year >= 1970) {
         for ($a = 1970 ; $a <= $year; $a++) {
            $leaf = _oss_is_leap_year($a);
            if ($leaf == true) {
               $loop_table = $_month_table_leaf;
               $_add_date = 366;
            } else {
               $loop_table = $_month_table_normal;
               $_add_date = 365;
            }
            if ($a < $year) { 
               $_total_date += $_add_date;
            } else {
               for($b=1;$b<$mon;$b++) {
                  $_total_date += $loop_table[$b];
               }
            }
         }
         $_total_date +=$day-1;
         $ret = $_total_date * $_day_power + $hr * $_hour_power + $min * $_min_power + $sec + $gmt_different;
      
      } else {
         for ($a = 1969 ; $a >= $year; $a--) {
            $leaf = _oss_is_leap_year($a);
            if ($leaf == true) {
               $loop_table = $_month_table_leaf;
               $_add_date = 366;
            } else {
               $loop_table = $_month_table_normal;
               $_add_date = 365;
            }
            if ($a > $year) { $_total_date += $_add_date;
            } else {
               for($b=12;$b>$mon;$b--) {
                  $_total_date += $loop_table[$b];
               }
            }
         }
         $_total_date += $loop_table[$mon] - $day;
         
         $_day_time = $hr * $_hour_power + $min * $_min_power + $sec;
         $_day_time = $_day_power - $_day_time;
         $ret = -( $_total_date * $_day_power + $_day_time - $gmt_different);
         if ($ret < -12220185600) $ret += 10*86400; // if < 5 Oct 1582, gregorian correction
         else if ($ret < -12219321600) $ret = -12219321600; // reset to 15 Oct 1582.
      } 
      return $ret;
   }
?>
