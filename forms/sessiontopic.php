<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page
$readperm = get_read_permision('user');
$writeperm = get_write_permision('user');

if (isset($_REQUEST['stid'])) {
    $stid = intval($_REQUEST['stid']);
} else {
    $stid =0;
}

if ($stid == 0) {
    //new
    $session_topics_id = 0;
    $session_topic_id = 0;
    $coaudit_session_id = 0;
    $topic_no = 0;
    $active = 0;
} else {
    //edit
    $topic = get_sessiontopic($stid);
    $session_topics_id = $topic['session_topics_id'];
    $session_topic_id = $topic['session_topic_id'];
    $coaudit_session_id = $topic['coaudit_session_id'];
    $topic_no = $topic['topic_no'];
    $active = $topic['active'];
}


//get data
$sessionres = get_all_session();
$topicres = get_all_topics();

$hidden[]=array('stid',$stid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=sessiontopic');
echo tableheader(_('Session topic'), 2);
echo tablerow_2col_dropbox(_('Coaudit session'), $sessionres, $coaudit_session_id, 'session_id', 'session_name', 0);
echo tablerow_2col_textbox(_('Topic No'), 'topic_no', $topic_no);
echo tablerow_2col_dropbox(_('Topic'), $topicres, $session_topic_id, 'session_topic_id', 'session_topic', 0);
echo tablerow_topics_active($active);
echo tablefooter_user(2, $session_topics_id, $writeperm);;
echo built_form_footer($hidden);
echo end_div();



?>