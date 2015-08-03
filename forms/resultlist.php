<?php

include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';

$db = new db_function();

//Check access to page
$readperm = get_read_permission('resultlist');
$writeperm = get_write_permission('resultlist');

$_SESSION['coauditor'] = array_key_exists('cid',$_REQUEST) ? $_REQUEST['cid'] : '';

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

$cid = array_key_exists('coauditor',$_SESSION) ? $_SESSION['coauditor'] : '';

if ($cid) {
    $cid = $_SESSION['user']['id'];
}

$year = 0;
$session = '';
$sessionold = '';
$sessionhead = '';
$rowheader1 = '';
$rowheader2 = '';
$datarow = '';
$col = 0;
$start = 0;
$assurer = '';

$session_topic_id = 0;
$coauditor_id = 0;

if (isset($_REQUEST['session_id'])) {
    $session = intval($_REQUEST['session_id']);
} else {
    $session = 0 ;
}

if (isset($_REQUEST['coauditor_id'])) {
    $coaudid = intval($_REQUEST['coauditor_id']);
} else {
    $coaudid = $cid;
}

if (isset($_REQUEST['session_year'])) {
    $sessionyear = intval($_REQUEST['session_year']);
} else {
    $sessionyear = 0;
}

$sessionres = $db -> get_all_session();
$coauditorres = $db -> get_all_user();
$sessionyearress =  $db -> get_session_year($session);

$hidden[]=array('cid',$cid);

echo start_div('content');

// build filter form
echo built_form_header(create_url('resultlist', 1));
echo tableheader(_('Filter'), 2);
echo tablerow_2col_dropbox(_('RA-Audit session'), $sessionres, $session, 'session_id', 'session_name', 1);
echo tablerow_2col_dropbox(_('Year'), $sessionyearress, $sessionyear, 'session_year', 'session_year', 1);

if ($cid == '') {
    echo tablerow_2col_dropbox(_('RA-Auditor'), $coauditorres, $coaudid, 'coauditor_id', 'coauditor_name', 1);
}

echo tablefooter_filter(2, _('Apply'));
echo built_form_footer($hidden);
echo empty_line();

foreach ($sessionres as $ressession) {
    if ($session == 0 || $ressession['session_id'] == $session ) {


        $res = $db->get_statistics_basic(' and `r`.`coauditsession_id` = ' . $ressession['session_id']);

        $year = 0;
        $datarow = '';
        $coltotal = 0;
        $start = 0;
        $nodetails = 0;

        //build new session table


        $headertopics = $db -> get_statistics_header(' and `sts`.`coaudit_session_id` = ' . $ressession['session_id']);
        $coltotal =get_statistics_col($headertopics) + 5;

        echo tableheader(sprintf(_('RA-Audit results for %s'),  $ressession['session_name']), $coltotal);
        $headertopics = $db -> get_statistics_header(' and `sts`.`coaudit_session_id` = ' . $ressession['session_id'] );
        echo result_header($headertopics);



        $res = $db->get_results($ressession['session_id'], $coaudid, $sessionyear);
        $sessionname = $ressession['session_name'];
        $col = 0;
        $start = 0;
        $total = array();

        // build result table
        foreach($res as $row){
            $linkadress = create_url('result', 1, array('sid' => $row['SessionID'], 'rid' =>  $row['uid'])) ;
            $editcell = tablecell( '<a href="' .$linkadress  .'">' . _('View') .'</a>');

            if ($start == 0 && $col == 0) {
                $assurer = $row['uid'];

                $datarow = tablecell($row['CYear']);
                $datarow .= tablecell($row['uid'],0,'right');
                $datarow .= tablecell($row['Coauditor']);
                $datarow .= tablecell($row['Country']);
                $datarow .= $editcell;
                $col = 4;
            }

            if ($assurer != $row['uid'] ) {
                if ($col > 0 && $start == 0) {
                    $start = 1;
                }

                $assurer = $row['uid'];

                echo tablerow_start() . $datarow . tablerow_end();

                $year = $row['CYear'];
                $datarow = tablecell($row['CYear']);
                $datarow .= tablecell($row['uid'],0,'right');
                $datarow .= tablecell($row['Coauditor']);
                $datarow .= tablecell($row['Country']);
                $datarow .= $editcell;
                array_key_exists(0 ,$total) ?  $total[0] += 1 : $total[0] = 1;
                $col = 4;
            }

            if ($start == 0) {
                $rowheader1 .= tablecell($row['Topic'],2);
                $rowheader2 .= tablecell(_('Result'));
                $rowheader2 .= tablecell(_('Comment'));
            }

            $datarow .= tablecell($row['Result'], 0,'center');
            $datarow .= tablecell($row['Comment']);


            array_key_exists($row['Topic_No'] ,$total) ?  $total[$row['Topic_No']] += $row['Result'] : $total[$row['Topic_No']] = $row['Result'];

            $col +=2;
        }

        if ($col > 0 ) {
            echo tablerow_start() . $datarow . tablerow_end();

            $datarow = tablecell(_(''));
            $datarow .= tablecell(_(''));
            $datarow .= tablecell(_(''));
            $datarow .= tablecell(_('Total'));

            array_key_exists(0 ,$total) ?  $total[0] += 1 : $total[0] = 1;

            $datarow .= tablecell($total[0] , 0, 'center');

            for ($i = 1; $i < count($total); $i++){
                $datarow .= tablecell($total[$i], 0, 'center');
                $datarow .= tablecell(_(''));
            }
            echo tablerow_start() . $datarow . tablerow_end();
        }

        echo table_end();
        echo empty_line();
        echo empty_line();
    }
}

echo end_div();
