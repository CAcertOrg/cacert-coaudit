<?php
include_once('../module/db_functions.php');
function tidystring($input){
    if ($input != "") {
        return trim(mysql_real_escape_string(stripslashes($input)));
    } else {
        return '';
    }
}

function define_roles(){
    $result = array();
    $result[] = _('Guest'); // 1
    $result[] = _('Coauditor'); // 2
    $result[] = _('Session administrator'); // 4
    $result[] = _('Administrator'); // 8
    $result[] = _('Auditor'); // 16
    return $result;
}

function get_read_permision( $view){
    $view_perm = get_view_right($view);
    if (!isset($view_perm['read_permission'])) {
        return 0;
    }
    $readview = $view_perm['read_permission'];
    if (($_SESSION['user']['read_permission'] & $readview)>0) {
        return 1;
    } else {
        return 0;
    }
}

function get_write_permision( $view){
    $view_perm = get_view_right($view);
    if (!isset($view_perm['write_permission'])) {
        return 0;
    }
    $writeview = $view_perm['write_permission'];
    if (($_SESSION['user']['write_permission'] & $writeview)>0) {
        return 1;
    } else {
        return 0;
    }
}

function test_data(){
    $_SESSION['user']['id'] = 1;
    $_SESSION['user']['name'] = 'norbert';
    $_SESSION['user']['email'] = 'norbert@cacert.org';
    $_SESSION['user']['read_permission'] = 15;
    $_SESSION['user']['write_permission'] = 14;
}
?>