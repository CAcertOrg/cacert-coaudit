<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
$roles = array('Guest','Coauditor', 'Admin', 'Auditor');

//Check access to page
$readperm = get_read_permision('user');
$writeperm = get_write_permision('user');

if (isset($_REQUEST['tid'])) {
    $tid = intval($_REQUEST['tid']);
} else {
    $tid =0;
}

if ($tid == 0) {
    //new user
    $session_topic_id = 0;
    $session_topic = '';
    $topic_explaination = '';
    $activ = 0;
} else {
    //edit user
    $topic = get_all_topics($tid);
    $session_topic_id = $topic['session_topic_id'];
    $session_topic = $topic['session_topic'];
    $topic_explaination = $topic['topic_explaination'];
    $activ = $topic['activ'];
}

//refresh user



$hidden[]=array('tid',$tid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=topic');
echo tableheader(_('Topic'), 2);
echo tablerow_2col_textbox(_('Topic'), 'topic', $session_topic);
echo tablerow_2col_textbox(_('Explaination'), 'explain', $topic_explaination);
echo tablerow_topics_active($activ);
echo tablefooter_user(2, $session_topic_id, $writeperm);;
echo built_form_footer($hidden);
echo end_div();


?>