<?php

session_start();

include_once '../module/basic_layout.php';
include_once '../module/basic_functions.php';
include_once '../module/login_functions.php';
include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';

$db = new db_function();
$assurerid = 0 ;
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
} else {
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
        $title = ' - ' . _('List of user');
        break;
    case 'user':
        $title = ' - ' . _('User');
        break;
    case 'topiclist':
        $title = ' - ' . _('List of topics');
        break;
    case 'topic':
        $title = ' - ' . _('Topic');
        break;
    case 'sessionlist':
        $title = ' - ' . _('List of coaudit sessions');
        break;
    case 'session':
        $title = ' - ' . _('Coaudit session');
        break;
    case 'sessiontopiclist':
        $title = ' - ' . _('List of session topics');
        break;
    case 'sessiontopic':
        $title = ' - ' . _('Session topic');
        break;
    case 'viewlist':
        $title = ' - ' . _('List of view');
        break;
    case 'view':
        $title = ' - ' . _('View');
        break;
    case 'resultlist':
        $title = ' - ' . _('Own result entries');
        break;
    case 'result':
        $title = ' - ' . _('Result');
        break;
    case 'statistic':
        $title = ' - ' . _('Statisics');
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

$userroles = count(define_roles()) - 1;

//user management
if ($type == 'userlist') {
    include '../forms/userlist.php';
}

if ($type == 'user') {
    include '../pages/user.php';
}

// topics management
if ($type == 'topiclist') {
    include '../forms/topiclist.php';
}

if ($type == 'topic') {
    include '../pages/topic.php';
}


// session management
if ($type == 'sessionlist') {
    include('../forms/sessionlist.php');
}

if ($type == 'session') {
    include '../pages/session.php';
}

// session topics management
if ($type == 'sessiontopiclist') {
    include '../forms/sessiontopiclist.php';
}

if ($type == 'sessiontopic') {
    include '../pages/sessiontopic.php';
}

//view management
if ($type == 'viewlist') {
    include '../forms/viewlist.php';
}

if ($type == 'view') {
    include '../pages/view.php';
}

// Enter result management
if ($type == 'resultlist') {
    include '../forms/resultlist.php';
}

if ($type == 'result') {
    include '../pages/result.php';
}

// statistic
if ($type == 'statistic') {
    include '../forms/statistic.php';
}

//imprint management
if ($type == 'imprint') {
    include '../forms/imprint.php';
}

//pki management
if ($type == 'kpilist') {
    include '../forms/kpilist.php';
}

if ($type == 'kpi') {
    include '../pages/kpi.php';
}

output_debug_box($_SESSION ['debug']);

echo footerend();
