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
echo end_div();
?>