<?php

$continue = true;

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
        if (isset( $_REQUEST['new'])) {
            $db->insert_session($session_name, $from, $to, $default );
        } else {
            $db->update_session($session_name, $from, $to, $default, $active, $sid);
        }

        include '../forms/sessionlist.php';

        $continue = false;
    } else {
        echo error($error);
    }
}

if (isset( $_REQUEST['sid'])) {
    $_SESSION['user']['sid'] = $_REQUEST['sid'];
} else {
    $_SESSION['user']['sid'] = 0;
}

if ($continue == true) {
    include('../forms/session.php');
}

?>