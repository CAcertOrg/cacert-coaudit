<?php
session_start();
include_once('../module/basic_layout.php');
include_once('../module/basic_functions.php');
include_once('../module/db_functions.php');
include_once('../module/login_functions.php');

dbstart();

//to be replaced by login functionality
if (isset( $_REQUEST['login'])) {
    $login = $type = $_REQUEST['login'];
    if ( $login == 'login') {
        login();
    }
    if ( $login == 'logout') {
        logout();
    }}

test_data();

if (isset( $_REQUEST['type'])) {
    $type = $_REQUEST['type'];
}else{
    $type='';
}

if (!isset($_SESSION['user']['read_permission'])) {
    $_SESSION['user']['read_permission'] =1;
}
if (!isset($_SESSION['user']['write_permission'])) {
    $_SESSION['user']['write_permission'] =1;
}

$title = '';

switch ($type) {
    case 'userlist':
        ;$title = ' - ' . _('List of user');
        break;
    case 'user':
        ;$title = ' - ' . _('User');
        break;
    case 'topiclist':
        ;$title = ' - ' . _('List of topics');
        break;
    case 'topic':
        ;$title = ' - ' . _('Topic');
        break;
    case 'sessionlist':
        ;$title = ' - ' . _('List of coaudit sessions');
        break;
    case 'session':
        ;$title = ' - ' . _('Coaudit session');
        break;
    case 'sessiontopiclist':
        ;$title = ' - ' . _('List of session topics');
        break;
    case 'sessiontopic':
        ;$title = ' - ' . _('Session topic');
        break;
    case 'viewlist':
        ;$title = ' - ' . _('List of view');
        break;
    case 'view':
        ;$title = ' - ' . _('View');
        break;
    case 'resultlist':
        ;$title = ' - ' . _('Own result entries');
        break;
    case 'result':
        ;$title = ' - ' . _('Result');
        break;
    case 'statistic':
        ;$title = ' - ' . _('Statisics');
        break;
    default:
        $title = '';
}

echo headerstart($title);

echo titlebar();

echo menu();

echo footer();

echo '<div class="error">' . $_SESSION['error'] . '</div>';

$_SESSION['error'] = '';

$userroles = count(define_roles())-1;

//user management
if ($type == 'userlist') {
    include('../forms/userlist.php');
}

if ($type == 'user') {
    $continue=true;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $cid = array_key_exists('cid',$_REQUEST) ? intval($_REQUEST['cid']) : '';
        $username = array_key_exists('username',$_REQUEST) ? tidystring($_REQUEST['username']) : '';
        $email = array_key_exists('email',$_REQUEST) ? tidystring($_REQUEST['email']) : '';
        for ($i = 0; $i <= $userroles; $i++){
            $readtest = array_key_exists('read'.$i,$_REQUEST) ? tidystring($_REQUEST['read'.$i]) : '';
            $writetest = array_key_exists('write'.$i,$_REQUEST) ? tidystring($_REQUEST['write'.$i]) : '';
            if ($readtest == 'on') {
                $read +=  pow(2, $i);
            }
            if ($writetest == 'on') {
                $write +=  pow(2, $i);
            }
        }

        //check valid data
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email == '';
        }

        if ($username == '' || $email == '') {
//missing data
        }

        if (isset( $_REQUEST['new'])){
            insert_user($username, $email, $read, $write, intval($_SESSION['user']['id']));
        } else {
            update_user($username, $email, $read, $write, intval($_SESSION['user']['id']), $cid);
        }
        include('../forms/userlist.php');
  //      http_redirect("index.php", array("type" => "userlist"), true, HTTP_REDIRECT_PERM);

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
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
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
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
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

//view management
if ($type == 'viewlist') {
    include('../forms/viewlist.php');
}

if ($type == 'view') {
    $continue=true;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $vid = array_key_exists('vid',$_REQUEST) ? intval($_REQUEST['vid']) : '';
        $view_name = array_key_exists('view_name',$_REQUEST) ? tidystring($_REQUEST['view_name']) : '';
        for ($i = 0; $i <= $userroles; $i++){
            $readtest = array_key_exists('read'.$i,$_REQUEST) ? tidystring($_REQUEST['read'.$i]) : '';
            $writetest = array_key_exists('write'.$i,$_REQUEST) ? tidystring($_REQUEST['write'.$i]) : '';
            if ($readtest == 'on') {
                $read +=  pow(2, $i);
            }
            if ($writetest == 'on') {
                $write +=  pow(2, $i);
            }
        }

        $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
        if ($activetest == 'on') {
            $active = 1;
        } else {
            $active = 0;
        }

        if (isset( $_REQUEST['new'])){
            insert_view($view_name, $read, $write);
        } else {
            update_view($view_name, $read, $write, $active, $vid);
        }

        include('../forms/viewlist.php');
        $continue=false;
    }
    if ($continue==true) {
        include('../forms/view.php');
    }
}

// Enter result management
if ($type == 'resultlist') {
    $_SESSION['coauditor'] = array_key_exists('cid',$_REQUEST) ? $_REQUEST['cid'] : '';

    include('../forms/resultlist.php');
}

if ($type == 'result') {
    $continue=true;

    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $rid = array_key_exists('rid',$_REQUEST) ? intval($_REQUEST['rid']) : '';
        $coaudit_session_id = array_key_exists('session_id',$_REQUEST) ? intval($_REQUEST['session_id']) : '';
        $primaryemail = array_key_exists('primaryemail',$_REQUEST) ? tidystring($_REQUEST['primaryemail']) : '';
        $isassurer = array_key_exists('assurer',$_REQUEST) ? tidystring($_REQUEST['assurer']) : '';
        if ($isassurer == 'on') {
            $isassurer = 1;
        } else {
            $isassurer = 0;
        }
        $expierencepoints = array_key_exists('expierencepoints',$_REQUEST) ? intval($_REQUEST['expierencepoints']) : '';
        if ($expierencepoints == '') {
            $expierencepoints = 0;
        }
        $country = array_key_exists('country',$_REQUEST) ? tidystring($_REQUEST['country']) : '';
        $location = array_key_exists('location',$_REQUEST) ? tidystring($_REQUEST['location']) : '';
        $coauditdate = array_key_exists('coauditdate',$_REQUEST) ? tidystring($_REQUEST['coauditdate']) : '';

        //check valid data
        if (!filter_var($primaryemail, FILTER_VALIDATE_EMAIL)) {
            // invalid emailaddress
            $primaryemail == '';
        }

        if (strlen($country) > 2) {
            $country = substr($country, 0, 2);
        }
        if (strlen($country) < 2) {
            $country = '';
        }
        $country = strtoupper($country);

        if (!validdate($coauditdate)) {
            $coauditdate ='';
        }


        if ($primaryemail == '' || $country == '' || $location == '' || $coauditdate == '') {
//missing data
        }

        $i = 1;
        $questions = array();
        while (isset($_REQUEST['qid' . $i])) {
            $tid = array_key_exists('qid' . $i, $_REQUEST) ? tidystring($_REQUEST['qid' . $i]): '';
            $chktest = array_key_exists('r' . $i,$_REQUEST) ? tidystring($_REQUEST['r' . $i]) : '';
            if ($chktest == 'on') {
                $r = 1;
            } else {
                $r = 0;
            }
            $c = array_key_exists('c' . $i, $_REQUEST) ? tidystring($_REQUEST['c' . $i]): '';
            $questions[] = array($tid, $r, $c);
            $i += 1;
        }



        if (isset( $_REQUEST['new'])){
            $assurerid = insert_result_user($primaryemail, $isassurer, $expierencepoints, $country, $location, $coauditdate);
            foreach($questions as $question){
                insert_result_topic($question[0], $coaudit_session_id, $assurerid, $question[1], $question[2]);
            }

        } else {
            update_view($view_name, $read, $write, $active, $vid);
        }

        include('../forms/result.php');
        $continue=false;
    }
    if ($continue==true) {
        include('../forms/result.php');
    }
}

// statistic
if ($type == 'statistic') {
    include('../forms/statistic.php');
}
echo footerend();

?>