<?php
session_start();
include_once('../module/basic_layout.php');
include_once('../module/basic_functions.php');
include_once('../module/login_functions.php');
include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

$_SESSION ['debug'] = '';

// login routine
if (isset( $_REQUEST['login'])) {
    $login = $type = $_REQUEST['login'];
    if ( $login == 'login') {
        login();
    }
    if ( $login == 'logout') {
        logout();
	header("location: https://" .  $_SERVER['HTTP_HOST'] . "/");
    }
}

//test_data();

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

echo footer();

echo titlebar();

echo menu();

if (array_key_exists('error', $_SESSION)) {
    echo '<div class="error">' . $_SESSION['error'] . '</div>';
}
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
            if ($readtest == "'on'") {
                $read +=  pow(2, $i);
            }
            if ($writetest == "'on'") {
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
            $db -> insert_user($username, $email, $read, $write, intval($_SESSION['user']['id']));
        } else {
            $db -> update_user($username, $email, $read, $write, intval($_SESSION['user']['id']), $cid);
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
        if ($activetest == "'on'") {
            $active = 1;
        } else {
            $active = 0;
        }
        if (isset( $_REQUEST['new'])){
            $db -> insert_topic($session_topic,$topic_explaination);
        } else {
            $db -> update_topic($session_topic, $topic_explaination, $active, $tid);
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
        $error = '';
        $sid = array_key_exists('sid',$_REQUEST) ? intval($_REQUEST['sid']) : '';
        $session_name = array_key_exists('session_name',$_REQUEST) ? tidystring($_REQUEST['session_name']) : '';
        $from = array_key_exists('from',$_REQUEST) ? tidystring($_REQUEST['from']) : '';
        $to = array_key_exists('to',$_REQUEST) ? tidystring($_REQUEST['to']) : '';
        if (!validdate($from)) {
            $error = _('Problem with from date') . '</br>';
        }
        if (!validdate($to)) {
            $error .= _('Problem with to date') . '</br>';
        }
        $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
        if ($activetest == "'on'") {
            $active = 1;
        } else {
            $active = 0;
        }
        $defaulttest = array_key_exists('default',$_REQUEST) ? tidystring($_REQUEST['default']) : '';
        if ($defaulttest == "'on'") {
            $default = 1;
        } else {
            $default = 0;
        }

        if ($error == '') {
            if (isset( $_REQUEST['new'])){
                $db -> insert_session($session_name, $from, $to, $default );
            } else {
                $db -> update_session($session_name, $from, $to, $default, $active, $sid);
            }
            include('../forms/sessionlist.php');
            $continue=false;
        } else {
            echo error($error);
        }

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
        if ($activetest == "'on'") {
            $active = 1;
        } else {
            $active = 0;
        }
        if (isset( $_REQUEST['new'])){
            $db -> insert_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no);
        } else {
            $db -> update_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no, $active, $session_topics_id);
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
            if ($readtest == "'on'") {
                $read +=  pow(2, $i);
            }
            if ($writetest == "'on'") {
                $write +=  pow(2, $i);
            }
        }

        $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
        if ($activetest == "'on'") {
            $active = 1;
        } else {
            $active = 0;
        }

        if (isset( $_REQUEST['new'])){
            $db -> insert_view($view_name, $read, $write);
        } else {
            $db -> update_view($view_name, $read, $write, $active, $vid);
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
    if (isset( $_REQUEST['change'])){
        $coaudit_session_id = array_key_exists('session_id',$_REQUEST) ? intval($_REQUEST['session_id']) : '';
        $_SESSION['user']['coaudit_session'] = $coaudit_session_id;
    }
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $error = '';
        $rid = array_key_exists('rid',$_REQUEST) ? intval($_REQUEST['rid']) : '';
        $coaudit_session_id = array_key_exists('session_id',$_REQUEST) ? intval($_REQUEST['session_id']) : '';
        $primaryemail = array_key_exists('primaryemail',$_REQUEST) ? tidystring($_REQUEST['primaryemail']) : '';
        $isassurer = array_key_exists('assurer',$_REQUEST) ? tidystring($_REQUEST['assurer']) : '';
        if ($isassurer == "'on'") {
            $isassurer = 1;
        } else {
            $isassurer = 0;
        }
        $expierencepoints = array_key_exists('expierencepoints',$_REQUEST) ? intval($_REQUEST['expierencepoints']) : 0;
        $country = array_key_exists('country',$_REQUEST) ? tidystring($_REQUEST['country']) : '';
        $location = array_key_exists('location',$_REQUEST) ? tidystring($_REQUEST['location']) : '';
        $coauditdate = array_key_exists('coauditdate',$_REQUEST) ? tidystring($_REQUEST['coauditdate']) : '';

        $primaryemail = str_replace("'", "", $primaryemail);
        $country = str_replace("'", "", $country);
        $location = str_replace("'", "", $location);
        $coauditdate = str_replace("'", "", $coauditdate);
        //check valid data
        if (!filter_var($primaryemail, FILTER_VALIDATE_EMAIL)) {
            // invalid emailaddress
            $primaryemail == '';
            $error .= _('Missing or wrong email address') . '<br>';
        }

        if (strlen($country) > 2) {
            $country = substr($country, 0, 2);
        }
        if (strlen($country) < 2) {
            $country = '';
            $error .= _('Missing or wrong country code') . '<br>';
        }
        $country = strtoupper($country);

        if (!validdate($coauditdate)) {
            $coauditdate ='';
            $error .= _('Missing or wrong coaudit date') . '<br>';
        }


        if ( $location == '' ) {
            $error .= _('Missing or wrong location') . '<br>';
        }

        $i = 1;
        $questions = array();
        while (isset($_REQUEST['qid' . $i])) {
            $tid = array_key_exists('qid' . $i, $_REQUEST) ? tidystring($_REQUEST['qid' . $i]): '';
            $chktest = array_key_exists('r' . $i,$_REQUEST) ? tidystring($_REQUEST['r' . $i]) : '';
            if ($chktest == "'on'") {
                $r = 1;
            } else {
                $r = 0;
            }
            $c = array_key_exists('c' . $i, $_REQUEST) ? tidystring($_REQUEST['c' . $i]):  "''";
            $questions[] = array($tid, $r, $c);
            $i += 1;
        }
        if ($error == '') {
            if (isset( $_REQUEST['new'])){
                $assurerid = $db -> insert_result_user($primaryemail, $isassurer, $expierencepoints, $country, $location, $coauditdate);
                foreach($questions as $question){
                    $db -> insert_result_topic($question[0], $coaudit_session_id, $assurerid, $question[1], $question[2]);
                }
            } else {
                $db -> update_result_user($primaryemail, $isassurer, $expierencepoints, $country, $location, $coauditdate, $rid);
                foreach($questions as $question){
                    $db ->  update_result_topic($question[0], $coaudit_session_id, $rid, $question[1], $question[2]);
                }
                $_SESSION['user']['rid'] = 0;

                $_SESSION['coauditor'] = array_key_exists('cid',$_REQUEST) ? $_REQUEST['cid'] : '';
                include('../forms/resultlist.php');
                exit;
            }
            $_SESSION['user']['coaudit_session'] = $coaudit_session_id;
            include('../forms/result.php');
            $continue=false;
        } else {
            echo error($error);
        }
    }
    if (isset( $_REQUEST['rid']) && isset( $_REQUEST['sid'])) {
        $_SESSION['user']['rid'] = $_REQUEST['rid'];
        $_SESSION['user']['coaudit_session'] = $_REQUEST['sid'];
    } else {
        $_SESSION['user']['rid']=0;
    }

    if (isset( $_REQUEST['delete'])){
        $db -> delete_result($_REQUEST['rid'], $_SESSION['user']['coaudit_session']) ;
        include('../forms/resultlist.php');
        $_SESSION['user']['rid'] = 0;
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

//imprint management
if ($type == 'imprint') {
    include('../forms/imprint.php');
}


//pki management
if ($type == 'kpilist') {
    include('../forms/kpilist.php');
}

if ($type == 'kpi') {
    $continue = true;
    $missing = false;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $kid = array_key_exists('kid',$_REQUEST) ? intval($_REQUEST['kid']) : '';
        $session_year = array_key_exists('session_year',$_REQUEST) ? intval($_REQUEST['session_year']) : '';
        $assurances = array_key_exists('assurances',$_REQUEST) ? intval($_REQUEST['assurances']) : '';
        $target = array_key_exists('target',$_REQUEST) ? intval($_REQUEST['target']) : '';
        $session_id = array_key_exists('session_id',$_REQUEST) ? intval($_REQUEST['session_id']) : '';


        if ( !$session_year || !$assurances || !$target ||
            $session_year == 0 || $assurances == 0 || $target == 0 ) {
            //missing data
            echo error(_('Some data is missing or not a number.'));

            $missing = true;
        }

        if ($missing == false) {
            if (isset( $_REQUEST['new']) && $missing == false){
                $db -> insert_kpi($session_id, $session_year, $assurances, $target);
            } else {
                $db -> update_kpi($session_id, $session_year, $assurances, $target, $kid);
            }
            include('../forms/kpilist.php');
            $continue = false;
        }

    }

    if ($continue == true) {
        include('../forms/kpi.php');
    }
}

output_debug_box($_SESSION ['debug']);

echo footerend();

?>
