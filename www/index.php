<?php

session_start();

include_once '../module/basic_layout.php';
include_once '../module/basic_functions.php';
include_once '../module/login_functions.php';
include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';

$db = new db_function();
$assurerid = 0;
$_SESSION ['debug'] = '';

$r_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
$qs = isset( $_SERVER['QUERY_STRING'] ) ? $_SERVER['QUERY_STRING'] : '';

$r_uri = explode('?', $r_uri, 2);
if ( 2 == count($r_uri) ) {
    list( $r_uri, $qs ) = $r_uri;
} else {
    list( $r_uri, $qs ) = array( $r_uri[0], '');
}

$r_uri = explode('/', $r_uri);

while (!empty($r_uri) && '' == $r_uri[0]) {
    array_shift($r_uri);
}

while (!empty($r_uri) && 'index.php' == $r_uri[0]) {
    array_shift($r_uri);
}

$secure = false;
if(!empty($r_uri) && 'secure' == $r_uri[0]) {
    array_shift($r_uri);
    $secure = true;
}

$type = !empty($r_uri) ? $r_uri[0] : '';
if( 1 < count($r_uri) ) {
    $type = '';
}

// If we are not logged in, there is also no user session!
if( !$secure ) {
    $debug = $_SESSION ['debug'];
    $_SESSION = array();
    $_SESSION ['debug'] = $debug;
}

$_SESSION ['debug'] .= ($secure ? 'secure' : '') . '<br/>';
$_SESSION ['debug'] .= htmlspecialchars($type) . '<br/>';

if ( $type == 'logout') {
    logout();
    header('Location: /');
    exit;
}

$funclist = array(
    //user management
    'userlist'		=> array(true,	'../forms/userlist.php',		_('List of user')),
    'user'		=> array(true,	'../pages/user.php',			_('User')),

    // topics management
    'topiclist'		=> array(true,	'../forms/topiclist.php',		_('List of topics')),
    'topic'		=> array(true,	'../pages/topic.php',			_('Topic')),

    // session management
    'sessionlist'	=> array(true,	'../forms/sessionlist.php',		_('List of coaudit sessions')),
    'session'		=> array(true,	'../pages/session.php',			_('Coaudit session')),

    // session topics management
    'sessiontopiclist'	=> array(true,	'../forms/sessiontopiclist.php',	_('List of session topics')),
    'sessiontopic'	=> array(true,	'../pages/sessiontopic.php',		_('Session topic')),

    //view management
    'viewlist'		=> array(true,	'../forms/viewlist.php',		_('List of view')),
    'view'		=> array(true,	'../pages/view.php',			_('View')),

    // Enter result management
    'resultlist'	=> array(true,	'../forms/resultlist.php',		_('Own result entries')),
    'result'		=> array(true,	'../pages/result.php',			_('Result')),

    // statistic
    'statistic'		=> array(false,	'../forms/statistic.php',		_('Statisics')),

    //imprint management
    'imprint'		=> array(false,	'../forms/imprint.php',			_('Imprint')),

    //pki management
    'kpilist'		=> array(true,	'../forms/kpilist.php',			_('List of KPI')),
    'kpi'		=> array(true,	'../pages/kpi.php',			_('KPI')),

    //Default page
    ''			=> array(false,	'',					'')
    );

if(!array_key_exists($type, $funclist)) {
    $type = '';
}

$needs_login = $funclist[$type][0];
$func = $funclist[$type][1];
$title = $funclist[$type][2];

// Enforce we are logged in when needed
if($needs_login && !$secure) {
    header('Location: ' . create_url($func, $needs_login, $_GET) );
    exit;
}

// If we are in the secure area, populate the certificate and user information
if( $secure ) {
    login();
}

if (!isset($_SESSION['user']['read_permission'])) {
    $_SESSION['user']['read_permission'] =1;
}
if (!isset($_SESSION['user']['write_permission'])) {
    $_SESSION['user']['write_permission'] =1;
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

if('' != $func && file_exists($func)) {
    include $func;
}

output_debug_box($_SESSION ['debug']);

echo footerend();
