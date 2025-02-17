<?php
  $img_path = $root_path . "/images/" ;
  if( $login == false ) {
     $template->set_filenames(array(
        'redirect' => 'redirect.tpl')
     );
     $qry = "select id, yr  from open.fin_year where dft is true " ;
     $rs=$data->getSelect ( $qry, $rA, 'S', __LINE__  );
     if (! ($rs->EOF) ){
        $template->assign_vars(array(
           'dfid' => $rs->f['id'],
           'dfyr' => $rs->f['yr'],
           'envhdr' => ($f_g == 1) ? '' : '<div id="bdy_g"  >', 
           'envftr' => ($f_g == 1) ? '' : '</div>'
        )) ;
     }
     $qry = "select id ,nam as nam from open.grp order by nam;" ;
     $rs=$data->getSelect ( $qry, $rA, 'S', __LINE__  );
     while (! ($rs->EOF) ){
        $template->assign_block_vars("grp",array(
           "id" =>$rs->f['id'],
           "nam" => $rs->f['nam']
        ));
        $rs->MoveNext();
     }
     $srv = (isset($_GET['srv'])) ? $_GET['srv'] : 0 ;
     $srv = (($srv == 1001) ||($srv == 997)) ? 0 : $srv ;
     $template->assign_vars(array(
        'dmsg' => isset($errmsg_g)? "<b>$errmsg_g</b>" : '',
        'srv' => $srv ,
        'job' => (isset($_GET['job'])) ? "&job=" . $_GET['job'] : '',
        'emp' => (isset($_GET['emp'])) ? "&emp=" . $_GET['emb'] : '',
        'usr' => (isset($_GET['usr'])) ? "&usr=" . $_GET['usr'] : '',
        'pol' => (isset($_GET['pol'])) ? "&pol=" . $_GET['pol'] : '',
        'nat' => (isset($_GET['nat'])) ? "&nat=" . $_GET['nat'] : '',
        'sbj' => (isset($_GET['sbj'])) ? "&sbj=" . $_GET['sbj'] : '',
        'tst' => (isset($_GET['tst'])) ? "&tst=" . $_GET['tst'] : '',
        'ans' => (isset($_GET['ans'])) ? "&ans=" . $_GET['ans'] : '',
        'qst' => (isset($_GET['qst'])) ? "&qst=" . $_GET['qst'] : '',
        'rsm' => (isset($_GET['rsm'])) ? "&rsm=" . $_GET['rsm'] : '',
        'js'  => (isset($_GET['js'] )) ? "&js="  . $_GET['js' ] : ''
     )) ;
  }
  $template->pparse('redirect');
?>
