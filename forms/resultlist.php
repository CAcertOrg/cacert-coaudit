<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');

//Check access to page
$readperm = get_read_permision('resultlist');
$writeperm = get_write_permision('resultlist');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

$cid = array_key_exists('coauditor',$_SESSION) ? $_SESSION['coauditor'] : '';

if ($cid == 'true') {
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

$res = get_results($session, $coaudid);

$sessionres = get_all_session();
$coauditorres = get_all_user();
$hidden[]=array('cid',$cid);


echo start_div('content');

// build filter form
echo built_form_header('../www/index.php?type=resultlist');
echo tableheader(_('Filter'), 2);
echo tablerow_2col_dropbox(_('Coaudit session'), $sessionres, $session, 'session_id', 'session_name', 1);
if ($cid == '') {
    echo tablerow_2col_dropbox(_('Co-Auditor'), $coauditorres, $coaudid, 'coauditor_id', 'coauditor_name', 1);
}
echo tablefooter_filter(2, _('Apply'));
echo built_form_footer($hidden);
echo empty_line();


// build result table
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
        $rowheader1 = tablecell(_(''));
        $rowheader1 .= tablecell(_(''));
        $rowheader1 .= tablecell(_(''));
        $rowheader2 = tablecell(_('Year'));
        $rowheader2 .= tablecell(_('Assurer'));
        $rowheader2 .= tablecell(_('Co-Auditor'));

        $datarow = tablecell($row['CYear']);
        $datarow .= tablecell($row['Assurer']);
        $datarow .= tablecell($row['Coauditor']);
        $col = 3;
    }
    if ($assurer != $row['Assurer'] ) {
        if ($col >0 && $start == 0) {
            echo tableheader(sprintf(_('Coaudit results for %s'), $sessionold), $col);
            echo tablerow_start() . $rowheader1 . tablerow_end();
            echo tablerow_start() . $rowheader2 . tablerow_end();
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
        $rowheader1 .= tablecell($row['Topic'],2);
        $rowheader2 .= tablecell(_('Result'));
        $rowheader2 .= tablecell(_('Comment'));
    }
    $datarow .= tablecell($row['Result']);
    $datarow .= tablecell($row['Comment'] . '%');
    $col +=2;
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