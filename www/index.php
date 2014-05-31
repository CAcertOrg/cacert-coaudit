<?php
session_start();
include_once('../module/basic_layout.php');
include_once('../module/basic_functions.php');
include_once('../module/db_functions.php');

$_SESSION['user']['id']=1;
dbstart();

if (isset( $_REQUEST['type'])) {
    $type = $_REQUEST['type'];
}else{
    $type='';
}

$title = '';

switch ($type) {
    case 'userlist':
        ;$title = ' - ' . _('Userlist');
        break;
    case 'user':
        ;$title = ' - ' . _('User');
        break;
    default:
        $title = '';
}

echo headerstart($title);

echo titlebar();

echo menu();

echo footer();

//user management
if ($type == 'userlist') {
    include('../forms/userlist.php');
}

if ($type == 'user') {
    $continue=true;
    if (isset( $_REQUEST['new']) | isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $cid = array_key_exists('cid',$_REQUEST) ? intval($_REQUEST['cid']) : '';
        $username = array_key_exists('username',$_REQUEST) ? tidystring($_REQUEST['username']) : '';
        $email = array_key_exists('email',$_REQUEST) ? tidystring($_REQUEST['email']) : '';
        for ($i = 0; $i <= 3; $i++){
            $readtest = array_key_exists('read'.$i,$_REQUEST) ? tidystring($_REQUEST['read'.$i]) : '';
            $writetest = array_key_exists('write'.$i,$_REQUEST) ? tidystring($_REQUEST['write'.$i]) : '';
            if ($readtest == 'on') {
                $read +=  pow(2, $i);
            }
            if ($writetest == 'on') {
                $write +=  pow(2, $i);
            }
        }
        if (isset( $_REQUEST['new'])){
            insert_user($username, $email, $read, $write, intval($_SESSION['user']['id']));
        } else {
            update_user($username, $email, $read, $write, intval($_SESSION['user']['id']), $cid);
        }
        include('../forms/userlist.php');
        $continue=false;
    }
    if (isset( $_REQUEST['cid'])) {
         $_SESSION['user']['cid'] = $_REQUEST['cid'];
    }else{
         $_SESSION['user']['cid']=0;
    }
    if ($continue==true) {
        include('../forms/user.php');
    }
}

// topics management
if ($type == 'topiclist') {
    include('../forms/topiclist.php');
}

if ($type == 'topic') {
    $continue=true;
    if (isset( $_REQUEST['new']) | isset( $_REQUEST['edit'])) {
        $tid = array_key_exists('tid',$_REQUEST) ? intval($_REQUEST['tid']) : '';
        $session_topic = array_key_exists('topic',$_REQUEST) ? tidystring($_REQUEST['topic']) : '';
        $topic_explaination = array_key_exists('explain',$_REQUEST) ? tidystring($_REQUEST['explain']) : '';
        $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
        if ($activetest == 'on') {
            $active = 1;
        } else {
            $active = 0;
        }
        if (isset( $_REQUEST['new'])){
            insert_topic($session_topic,$topic_explaination);
        } else {
            update_topic($session_topic, $topic_explaination, $active, $tid);
        }
        include('../forms/topiclist.php');
        $continue=false;
    }
    if (isset( $_REQUEST['tid'])) {
        $_SESSION['user']['tid'] = $_REQUEST['tid'];
    }else{
        $_SESSION['user']['tid']=0;
    }
    if ($continue==true) {
        include('../forms/topic.php');
    }
}


// session management
if ($type == 'sessionlist') {
    include('../forms/sessionlist.php');
}

if ($type == 'session') {
    $continue=true;
    if (isset( $_REQUEST['new']) | isset( $_REQUEST['edit'])) {
        $sid = array_key_exists('sid',$_REQUEST) ? intval($_REQUEST['sid']) : '';
        $session_name = array_key_exists('session_name',$_REQUEST) ? tidystring($_REQUEST['session_name']) : '';
        $from = array_key_exists('from',$_REQUEST) ? tidystring($_REQUEST['from']) : '';
        $to = array_key_exists('to',$_REQUEST) ? tidystring($_REQUEST['to']) : '';
        $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
        if ($activetest == 'on') {
            $active = 1;
        } else {
            $active = 0;
        }
        if (isset( $_REQUEST['new'])){
            insert_session($session_name, $from, $to );
        } else {
            update_session($session_name, $from, $to, $active, $sid);
        }
        include('../forms/sessionlist.php');
        $continue=false;
    }
    if (isset( $_REQUEST['sid'])) {
        $_SESSION['user']['sid'] = $_REQUEST['sid'];
    }else{
        $_SESSION['user']['sid']=0;
    }
    if ($continue==true) {
        include('../forms/session.php');
    }
}


// session topics management
if ($type == 'sessiontopiclist') {
    include('../forms/sessiontopiclist.php');
}

if ($type == 'sessiontopic') {
    $continue=true;
    if (isset( $_REQUEST['new']) | isset( $_REQUEST['edit'])) {
        $session_topics_id = array_key_exists('stid',$_REQUEST) ? intval($_REQUEST['stid']) : '';
        $session_topic_id = array_key_exists('session_topic_id',$_REQUEST) ? intval($_REQUEST['session_topic_id']) : '';
        $coaudit_session_id = array_key_exists('session_id',$_REQUEST) ? intval($_REQUEST['session_id']) : '';
        $topic_no = array_key_exists('topic_no',$_REQUEST) ? intval($_REQUEST['topic_no']) : '';
        $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
        if ($activetest == 'on') {
            $active = 1;
        } else {
            $active = 0;
        }
        if (isset( $_REQUEST['new'])){
            insert_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no);
        } else {
            update_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no, $active, $session_topics_id);
        }
        include('../forms/sessiontopiclist.php');
        $continue=false;
    }
    if (isset( $_REQUEST['stid'])) {
        $_SESSION['user']['stid'] = $_REQUEST['stid'];
    }else{
        $_SESSION['user']['stid']=0;
    }
    if ($continue==true) {
        include('../forms/sessiontopic.php');
    }
}



echo footerend();

?>