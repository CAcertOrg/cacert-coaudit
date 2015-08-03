<?php

include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';
include '../module/applicationconfig.php';

$db = new db_function();

//Check access to page
$readperm = get_read_permission('statistic');
$writeperm = get_write_permission('statistic');

$urlsecure = 0;
if ($secure) {
    $urlsecure = 1;
}

$ressessions = $db->get_statisics_sessions();


if (array_key_exists('csid',$_SESSION['user'])) {
    $csid = $_SESSION['user']['csid'];
} else {
    $cs_res= $db -> get_all_session('WHERE `default` = 1');
    $csid = $cs_res['session_id'];
}
$hidden[] = array('csid', $csid);

//build form to choose session
echo start_div('content');

echo built_form_header(create_url('statistic', $urlsecure, array('csid' => $csid)));
echo tableheader(_('Choose RA-Audit session'), 2);
echo tablerow_2col_dropbox_apply(_('Session'), $ressessions, $csid, 'session_id', 'session_name', 'change', _('Update'), 0);
echo table_end();
echo built_form_footer($hidden);
echo empty_line();

//build country table
$res = $db->get_statistics_country(' and `r`.`coauditsession_id` = ' . $csid);

$year = '';
$session = '';
$datarow = '';
$col = 0;
$coltotal = 0;
$start = 0;
$total = 0;
$nodetails = 0;
$nodetailcounty = array();

foreach($res as $row) {
    //new table (year)
    if ($session != $row['CYear'] ) {
        $session = $row['CYear'];

        // close table previous year
        if ($start > 0) {
            if ( !$nodetails){
                $nodetailcounty[] = $datarow .= tablecell(_('Not enough data for detail output'), $col - 2,'center');
            } else {
                echo tablerow_start() . $datarow . tablerow_end();
            }

            if (isset($nodetailcounty)) {
                foreach($nodetailcounty as $countryline){
                    echo tablerow_start() . $countryline . tablerow_end();
                }
            }

            echo tablerow_start() . tablecell(sprintf(_('Total test: %s'), $total), $coltotal, 'center') . tablerow_end();
            echo table_end();
            echo empty_line();
            $total = 0;
        }
        $headertopics = $db -> get_statistics_header(' and `sts`.`coaudit_session_id` = ' . $csid );
        $coltotal =get_statistics_col($headertopics) +2;
        echo tableheader(sprintf(_('Coaudit results for %s'), $session), $coltotal);

        $headertopics = $db -> get_statistics_header(' and `sts`.`coaudit_session_id` = ' . $csid );
        echo statistics_header($headertopics);

        $start = 0;
        $year = '';
        $nodetailcounty = array();
    }
    //new table row
    if ($year != $row['Country'] ) {
        $year = $row['Country'];

        if (0 == $start) {
            $start = 1;
            $col = 1;

        }

        if ($col > 1 ) {
            if ( !$nodetails){
                $nodetailcounty[] = $datarow .= tablecell(_('Not enough data for detail output'), $col - 2,'center');
            } else {
                echo tablerow_start() . $datarow . tablerow_end();
            }
        }

        $year = $row['Country'];
        $datarow = tablecell($row['Country']);
        $datarow .= tablecell($row['Total'],0,'center');
        $total += $row['Total'];
        $col = 2;
        $nodetails = 0;
    }
    //fill table row
    if ( $row['Total'] >= $number_per_country) {
        $datarow .= tablecell($row['res'],0,'center');
        $datarow .= tablecell(number_format($row['Perc'], 1, '.', '') . '%',0,'right');
        $nodetails = 1;
    }
    $col +=2;

}

//close last table
if ( !$nodetails){
    $nodetailcounty[] = $datarow .= tablecell(_('Not enough data for detail output'), $col - 2,'center');
} else {
    echo tablerow_start() . $datarow . tablerow_end();
}

if (isset($nodetailcounty)) {
    foreach($nodetailcounty as $countryline){
        echo tablerow_start() . $countryline . tablerow_end();
    }
}

echo tablerow_start() . tablecell(sprintf(_('Total test: %s'), $total), $coltotal, 'center') . tablerow_end();
echo table_end();
echo empty_line();


echo end_div();


