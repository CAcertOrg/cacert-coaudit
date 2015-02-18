<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('statistic');
$writeperm = get_write_permission('statistic');

$kpidata = array();

$ressessions = $db -> get_statisics_sessions();
//buildform
echo start_div('content');

foreach ($ressessions as $ressession) {

    //build seesion table
    $res = $db -> get_statiscs_basic(' and `r`.`coauditsession_id` = ' . $ressession['session_id']);

    $year = 0;
    $session = '';
    $sessionold = '';
    $sessionhead = '';
    $rowheader1 = '';
    $rowheader2 = '';
    $datarow = '';
    $col = 0;
    $start = 0;

    foreach($res as $row){
        if ($session != $row['Session'] ) {
            $sessionold = $session;
            $session = $row['Session'];
            if ($start == 0 && $col == 0) {
                $sessionold = $session;
            }
            $start = 0;
        }
        if ($year != $row['CYear'] ) {
            if ($col >0 && $start == 0) {
                if ($sessionold != $session) {
                    echo tablerow_start() . $datarow . tablerow_end();
                    echo table_end();
                    echo empty_line();
                }
                echo tableheader(sprintf(_('Coaudit results for %s'), $sessionold), $col);
                echo tablerow_start() . $rowheader1 . tablerow_end();
                echo tablerow_start() . $rowheader2 . tablerow_end();
                $start = 1;
                $col = 1;
            }
            if ($col >0 && $sessionold == $session ) {
                echo tablerow_start() . $datarow . tablerow_end();
            }
            $year = $row['CYear'];
            $datarow = tablecell($row['CYear']);
            $datarow .= tablecell($row['Total'],0,'center');
            $rowheader1 = tablecell('');
            $rowheader1 .= tablecell('');
            $rowheader2 = tablecell(_('Year'));
            $rowheader2 .= tablecell(_('Tests'));
            $kpidata[] = array($row['CYear'],$row['Total']);
            $col = 2;
        }
        if ($_SESSION['user']['read_permission']>1) {
             $rowheader1 .= tablecell($row['Topic'],2);
        } else {
            $rowheader1 .= tablecell('Q' . $row['Topic_No'],2);
        }
        $rowheader2 .= tablecell(_('Passed'));
        $rowheader2 .= tablecell(_('Percentage'));
        $datarow .= tablecell($row['res'],0,'center');
        $datarow .= tablecell(number_format($row['Perc'], 1, '.', '') . '%',0,'right');
        $col +=2;
    }
    if ($start == 0) {
        echo tableheader(sprintf(_('Coaudit results for %'), $sessionold), $col);
        echo tablerow_start() . $rowheader1 . tablerow_end();
        echo tablerow_start() . $rowheader2 . tablerow_end();
        $start = 1;
    }
    echo tablerow_start() . $datarow . tablerow_end();
    echo table_end();
    echo empty_line();


    //KPI statistic
    echo empty_line();


    $res = $db -> get_statiscs_kpi( $ressession['session_id']);
    echo tableheader(_('Coaudit KPI for ' . $ressession['session_name']),5);
    $rowheader = tablerow_start();
    $rowheader .= tablecell(_('Year'));
    $rowheader .= tablecell(_('Tests'));
    $rowheader .= tablecell(_('Assurances'));
    $rowheader .= tablecell(_('Percentage'));
    $rowheader .= tablecell(_('Target KPI')) . tablerow_end();
    echo $rowheader;
    foreach($res as $row){
        $stest = 0;
        foreach ($kpidata as $syear) {
            if ($syear[0] == $row['session_year']) {
                $stest = $syear[1];
            }
        }
        $datarow = tablecell($row['session_year']);
        $datarow .= tablecell($stest, 0,'center');
        $datarow .= tablecell($row['assurances'], 0,'center');
        $datarow .= tablecell(number_format(($stest / $row['assurances']) * 100, 1, '.', '') . '%', 0,'center');
        $datarow .= tablecell(number_format($row['target'], 1, '.', '') . '%', 0,'center');
        echo tablerow_start() . $datarow . tablerow_end();
    }
    echo table_end();
    echo empty_line();
}
echo end_div();
?>