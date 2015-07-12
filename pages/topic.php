<?php

$continue = true;
if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
    $tid = array_key_exists('tid',$_REQUEST) ? intval($_REQUEST['tid']) : '';
    $session_topic = array_key_exists('topic',$_REQUEST) ? tidystring($_REQUEST['topic']) : '';
    $topic_explaination = array_key_exists('explain',$_REQUEST) ? tidystring($_REQUEST['explain']) : '';
    $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';

    if ($activetest == "'on'") {
        $active = 1;
    } else {
        $active = 0;
    }

    if (isset( $_REQUEST['new'])){
        $db->insert_topic($session_topic,$topic_explaination);
    } else {
        $db->update_topic($session_topic, $topic_explaination, $active, $tid);
    }

    include '../forms/topiclist.php';

    $continue = false;
}

if (isset( $_REQUEST['tid'])) {
    $_SESSION['user']['tid'] = $_REQUEST['tid'];
} else {
    $_SESSION['user']['tid']=0;
}

if ($continue) {
    include '../forms/topic.php';
}

?>