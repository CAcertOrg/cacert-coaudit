<?php
include_once('../module/output_functions.php');
include_once('../module/db_functions.php');

//Check access to page
$readperm = get_read_permision('session');
$writeperm = get_write_permision('session');


if (isset($_REQUEST['sid'])) {
    $sid = intval($_REQUEST['sid']);
} else {
    $sid =0;
}

if ($sid == 0) {
    //new session
    $session_id = 0;
    $session_name = '';
    $from = '';
    $to = '';
    $active = 0;
} else {
    //edit session
    $sessions = get_all_session($sid);
    $session_id = $sessions['session_id'];
    $session_name = $sessions['session_name'];
    $from = $sessions['from'];
    $to = $sessions['to'];
    $active = $sessions['active'];
}


$hidden[]=array('sid',$sid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=session');
echo tableheader(_('Session'), 2);
echo tablerow_2col_textbox(_('Session'), 'session_name', $session_name);
echo tablerow_2col_textbox(_('From'), 'from', $from);
echo tablerow_2col_textbox(_('To'), 'to', $to);
echo tablerow_topics_active($active);
echo tablefooter_user(2, $sid, $writeperm);;
echo built_form_footer($hidden);
echo end_div();


?>