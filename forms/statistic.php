<?php

include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';
include '../module/applicationconfig.php';

$db = new db_function();

//Check access to page
$readperm = get_read_permission('statistic');
$writeperm = get_write_permission('statistic');

$kpidata = array();

$ressessions = $db->get_statisics_sessions();

//buildform
echo start_div('content');

foreach ($ressessions as $ressession) {

    $res = $db->get_statistics_basic(' and `r`.`coauditsession_id` = ' . $ressession['session_id']);

    $year = 0;
    $datarow = '';
    $coltotal = 0;
    $start = 0;
    $nodetails = 0;

    //build new session table


    $headertopics = $db -> get_statistics_header(' and `sts`.`coaudit_session_id` = ' . $ressession['session_id']);
    $coltotal =get_statistics_col($headertopics) + 2;

    echo tableheader(sprintf(_('Coaudit results for %s'),  $ressession['session_name']), $coltotal);
    $headertopics = $db -> get_statistics_header(' and `sts`.`coaudit_session_id` = ' . $ressession['session_id'] );
    echo statistics_header($headertopics);

    foreach($res as $row) {

        if ($year != $row['CYear'] ) {

            //write datarow if not new table
            if ($start) {
                if ( !$nodetails){
                    $datarow .= tablecell(_('Not enough data for detail output'), $coltotal - 2,'center');
                }
                echo tablerow_start() . $datarow . tablerow_end();
            }

            //create start of new datarow
            $start = 1;
            $year = $row['CYear'];
            $datarow = tablecell($row['CYear']);
            $datarow .= tablecell($row['Total'],0,'center');
            $kpidata[] = array($row['CYear'],$row['Total']);
            $nodetails = 0;
        }

        //fill table row
        if ( $row['Total'] >= $number_per_country) {
            $datarow .= tablecell($row['res'],0,'center');
            $datarow .= tablecell(number_format($row['Perc'], 1, '.', '') . '%',0,'right');
            $nodetails = 1;
        }

    }

    //close session table
    if ( !$nodetails){
        $datarow .= tablecell(_('Not enough data for detail output'), $coltotal - 2,'center');
    }

    echo tablerow_start() . $datarow . tablerow_end();
    echo table_end();
    echo empty_line();

    //KPI statistic

    $res = $db -> get_statistics_kpi( $ressession['session_id']);

    echo tableheader(_('Coaudit KPI for ' . $ressession['session_name']),5);

    $rowheader = tablerow_start();
    $rowheader .= tablecell(_('Year'));
    $rowheader .= tablecell(_('Tests'));
    $rowheader .= tablecell(_('Assurances'));
    $rowheader .= tablecell(_('Percentage'));
    $rowheader .= tablecell(_('Target KPI')) . tablerow_end();

    echo $rowheader;

    foreach($res as $row) {
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
    echo empty_line();
}

echo end_div();
