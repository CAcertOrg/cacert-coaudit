<?php

$continue = true;
if (isset( $_REQUEST['new']) | isset( $_REQUEST['edit'])) {
    $session_topics_id = array_key_exists('stid',$_REQUEST) ? intval($_REQUEST['stid']) : '';
    $session_topic_id = array_key_exists('session_topic_id',$_REQUEST) ? intval($_REQUEST['session_topic_id']) : '';
    $coaudit_session_id = array_key_exists('session_id',$_REQUEST) ? intval($_REQUEST['session_id']) : '';
    $topic_no = array_key_exists('topic_no',$_REQUEST) ? intval($_REQUEST['topic_no']) : '';
    $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
    if ($activetest == "'on'") {
        $active = 1;
    } else {
        $active = 0;
    }
    if (isset( $_REQUEST['new'])){
        $db->insert_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no);
    } else {
        $db->update_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no, $active, $session_topics_id);
    }

    include '../forms/sessiontopiclist.php';

    $continue = false;
}

if (isset( $_REQUEST['stid'])) {
    $_SESSION['user']['stid'] = $_REQUEST['stid'];
} else {
    $_SESSION['user']['stid'] = 0;
}

if ($continue) {
    include '../forms/sessiontopic.php';
}
