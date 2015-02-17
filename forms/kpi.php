<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();
//Check access to page
$readperm = get_read_permission('kpi');
$writeperm = get_write_permission('kpi');

if (isset($_REQUEST['kid'])) {
    $kid = intval($_REQUEST['kid']);
} else {
    $kid = 0;
}

if ($kid == 0) {
    //new kpi
    $session_id = '';
    $session_year = '';
    $assurances = '';
    $target = '';
} else {
    //edit kpi
    $kpi = $db -> get_all_kpi(" coaudit_refdata_id = $kid");
    $session_id = $kpi['session_id'];
    $session_year = $kpi['session_year'];
    $assurances = $kpi['assurances'];
    $target = $kpi['target'];
}

//refresh refresh

$sessionres = $db -> get_all_session();

$hidden[]=array('kid',$kid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../index.php?type=kpi');
echo tableheader(_('KPI'), 2);
echo tablerow_2col_dropbox(_('Coaudit session'), $sessionres, $session_id, 'session_id', 'session_name', 0);
echo tablerow_2col_textbox(_('Year'), 'session_year', $session_year);
echo tablerow_2col_textbox(_('Assurance'), 'assurances', $assurances);
echo tablerow_2col_textbox(_('Target [%]'), 'target', $target);
echo tablefooter_user(2, $kid, $writeperm);
echo built_form_footer($hidden);
echo end_div();


?>