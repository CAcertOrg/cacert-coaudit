<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
$roles = array('Guest','Coauditor', 'Admin', 'Auditor');

//Check access to page
/*
   if (isset($_SESSION['user']['cid'])) {
   $cid = intval($_SESSION['user']['cid']);
   } else {
   $cid =0;
   }
*/

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
echo built_form_header('../www/index.php?type=topic');
echo tableheader('Topic', 2);
echo tablerow_2col_textbox('Topic', 'topic', $session_topic);
echo tablerow_2col_textbox('Explaination', 'explain', $topic_explaination);
echo tablerow_topics_active($activ);
echo tablefooter_user(2, $session_topic_id);
echo built_form_footer($hidden);
echo end_div();


?>