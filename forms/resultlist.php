<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');

//Check access to page
$readperm = get_read_permision('result');
$writeperm = get_write_permision('result');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
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

$res = get_results();

echo start_div('content');

while($row = mysql_fetch_assoc($res)){
    if ($session != $row['Session'] ) {
        $sessionold = $session;
        $session = $row['Session'];
        $assurer = $row['Assurer'];
        if ($start == 0 ) {
            $sessionold = $session;
        }
        $start = 0;
    }


    if ($start == 0 && $col == 0) {
        $rowheader1 = tablecell(_('Year'));
        $rowheader1 .= tablecell(_('Assurer'));
        $rowheader1 .= tablecell(_('Co-Auditor'));
        $datarow = tablecell($row['CYear']);
        $datarow .= tablecell($row['Assurer']);
        $datarow .= tablecell($row['Coauditor']);
        $col = 3;
    }
    if ($assurer != $row['Assurer'] ) {
        if ($col >0 && $start == 0) {
            echo tableheader(sprintf(_('Coaudit results for %s'), $sessionold), $col);
            echo tablerow_start() . $rowheader1 . tablerow_end();
            $start = 1;
        }
        $assurer = $row['Assurer'];
        echo tablerow_start() . $datarow . tablerow_end();
        $year = $row['CYear'];
        $datarow = tablecell($row['CYear']);
        $datarow .= tablecell($row['Assurer']);
        $datarow .= tablecell($row['Coauditor']);
        $col = 3;
    }


    if ($start == 0) {
        $rowheader1 .= tablecell($row['Topic'],1);
    }
    $datarow .= tablecell($row['Result']);
    //    $datarow .= tablecell($row['Perc'] . '%');
    $col +=1;
}

if ($start == 0) {
    echo tableheader(sprintf(_('Coaudit results for %'), $sessionold), $col);
    echo tablerow_start() . $rowheader1 . tablerow_end();
    $start = 1;
}
echo tablerow_start() . $datarow . tablerow_end();
echo table_end();

echo empty_line();
echo empty_line();

echo end_div();

?>