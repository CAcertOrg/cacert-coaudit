<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');

//Check access to page
$readperm = get_read_permision('statistic');
$writeperm = get_write_permision('statistic');

$res = get_statiscs_basic();

$year = 0;
$session = '';
$sessionold = '';
$sessionhead = '';
$rowheader1 = '';
$rowheader2 = '';
$datarow = '';
$col = 0;
$start = 0;
$kpidata = array();
//buildform
echo start_div('content');

while($row = mysql_fetch_assoc($res)){
    if ($session != $row['Session'] ) {
        $sessionold = $session;
        $session = $row['Session'];
        if ($start == 0 ) {
            $sessionold = $session;
        }
        $start = 0;
    }
    if ($year != $row['CYear'] ) {
        if ($col >0 && $start == 0) {
            echo tableheader(sprintf(_('Coaudit results for %s'), $sessionold), $col);
            echo tablerow_start() . $rowheader1 . tablerow_end();
            echo tablerow_start() . $rowheader2 . tablerow_end();
            $start = 1;
        }
        if ($col >0 ) {
            echo tablerow_start() . $datarow . tablerow_end();
        }
        $year = $row['CYear'];
        $datarow = tablecell($row['CYear']);
        $datarow .= tablecell($row['Total']);
        $rowheader1 = tablecell('');
        $rowheader1 .= tablecell('');
        $rowheader2 = tablecell(_('Year'));
        $rowheader2 .= tablecell(_('Tests'));
        $kpidata[] = array($row['CYear'],$row['Total']);
        $col = 2;
    }
    if ($start == 0) {
        if ($_SESSION['user']['read_permission']>1) {
             $rowheader1 .= tablecell($row['Topic'],2);
        } else {
            $rowheader1 .= tablecell('Q' . $row['Topic_No'],2);
        }
        $rowheader2 .= tablecell(_('Passed'));
        $rowheader2 .= tablecell(_('Percentage'));
    }
    $datarow .= tablecell($row['res']);
    $datarow .= tablecell(number_format($row['Perc'], 1, '.', '') . '%');
//    $datarow .= tablecell($row['Perc'] . '%');
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


$res = get_statiscs_kpi(2);
echo tableheader(_('Coaudit KPI'),5);
$rowheader = tablerow_start();
$rowheader .= tablecell(_('Year'));
$rowheader .= tablecell(_('Tests'));
$rowheader .= tablecell(_('Assurances'));
$rowheader .= tablecell(_('Percentage'));
$rowheader .= tablecell(_('Taget KPI')) . tablerow_end();
echo $rowheader;
while($row = mysql_fetch_assoc($res)){
    $stest = 0;
    foreach ($kpidata as $syear) {
        if ($syear[0] == $row['session_year']) {
            $stest = $syear[1];
        }
    }
    $datarow = tablecell($row['session_year']);
    $datarow .= tablecell($stest);
    $datarow .= tablecell($row['assurances']);
    $datarow .= tablecell(number_format(($stest / $row['assurances']) * 100, 1, '.', '') . '%');
    $datarow .= tablecell(number_format($row['target'], 1, '.', '') . '%');
    echo tablerow_start() . $datarow . tablerow_end();
}
echo table_end();
echo end_div();
?>