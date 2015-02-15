<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('resultlist');
$writeperm = get_write_permission('resultlist');

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




$sessionres = $db -> get_all_session();
$coauditorres = $db -> get_all_user();
$hidden[]=array('cid',$cid);


echo start_div('content');

// build filter form
echo built_form_header('../index.php?type=resultlist');
echo tableheader(_('Filter'), 2);
echo tablerow_2col_dropbox(_('Coaudit session'), $sessionres, $session, 'session_id', 'session_name', 1);
if ($cid == '') {
    echo tablerow_2col_dropbox(_('Co-Auditor'), $coauditorres, $coaudid, 'coauditor_id', 'coauditor_name', 1);
}
echo tablefooter_filter(2, _('Apply'));
echo built_form_footer($hidden);
echo empty_line();


foreach ($sessionres as $ressession) {
    if ($session == 0 || $ressession['session_id'] == $session ) {

        $res = $db -> get_results($ressession['session_id'], $coaudid);
        $sessionname = $ressession['session_name'];
        $col = 0;
        $start = 0;

        // build result table
        foreach($res as $row){
            $linkadress = '../index.php?type=result&sid=' .  $row['SessionID'] .'&rid=';
            $editcell = tablecell( '<a href="' .$linkadress . $row['uid'] .'">' . _('View') .'</a>');

            if ($start == 0 && $col == 0) {
                $assurer = $row['uid'];
                $rowheader1 = tablecell(_(''));
                $rowheader1 .= tablecell(_(''));
                $rowheader1 .= tablecell(_(''));
                $rowheader1 .= tablecell(_(''));
                $rowheader1 .= tablecell(_(''));
                $rowheader2 = tablecell(_('Year'));
                $rowheader2 .= tablecell(_('Assurer'));
                $rowheader2 .= tablecell(_('ID'));
                $rowheader2 .= tablecell(_('Co-Auditor'));
                $rowheader2 .= tablecell(_('View'));

                $datarow = tablecell($row['CYear']);
                $datarow .= tablecell($row['Assurer']);
                $datarow .= tablecell($row['uid'],0,'right');
                $datarow .= tablecell($row['Coauditor']);
                $datarow .= $editcell;
                $col = 4;
            }
            if ($assurer != $row['uid'] ) {
                if ($col >0 && $start == 0) {
                    echo tableheader(sprintf(_('Coaudit results for %s'), $sessionname), $col);
                    echo tablerow_start() . $rowheader1 . tablerow_end();
                    echo tablerow_start() . $rowheader2 . tablerow_end();
                    $start = 1;
                }
                $assurer = $row['uid'];
                echo tablerow_start() . $datarow . tablerow_end();
                $year = $row['CYear'];
                $datarow = tablecell($row['CYear']);
                $datarow .= tablecell($row['Assurer']);
                $datarow .= tablecell($row['uid'],0,'right');
                $datarow .= tablecell($row['Coauditor']);
                $datarow .= $editcell;
                $col = 4;
            }


            if ($start == 0) {
                $rowheader1 .= tablecell($row['Topic'],2);
                $rowheader2 .= tablecell(_('Result'));
                $rowheader2 .= tablecell(_('Comment'));
            }
            $datarow .= tablecell($row['Result'], 0,'center');
            $datarow .= tablecell($row['Comment']);
            $col +=2;
        }


        if ($start == 0 ) {
            echo tableheader(sprintf(_('Coaudit results for %s'), $sessionname), $col);
        if ($col > 0 ) {
            echo tablerow_start() . $rowheader1 . tablerow_end();
            echo tablerow_start() . $rowheader2 . tablerow_end();
        }
           }
        if ($col > 0 ) {
            echo tablerow_start() . $datarow . tablerow_end();
        }
        echo table_end();
        echo empty_line();
        echo empty_line();
    }
}

echo end_div();

?>