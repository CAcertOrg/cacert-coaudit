<?php

include_once '../module/class.db_functions.php';

/**
 * tidystring()
 * cleans a string
 * @param mixed $input
 * @return
 */
function tidystring($input) {
    if ($input != "") {
        $input = trim(stripslashes($input));
        $db = new db_function();
        return $db->tidystring($input);
    } else {
        return '';
    }
}

/**
 * define_roles()
 * returns the array for the user roles
 * @return
 */
function define_roles(){
    $result = array();
    $result[] = _('Guest'); // 1
    $result[] = _('RA-Auditor'); // 2
    $result[] = _('Session administrator'); // 4
    $result[] = _('Administrator'); // 8
    $result[] = _('Auditor'); // 16
    return $result;
}

/**
 * get_read_permission()
 * returns the reade permission for a requested view
 * @param mixed $view
 * @return
 */
function get_read_permission($view) {
    if (!isset($_SESSION['readperm'][$view])){
        set_permission_session();
    }
    if ((intval($_SESSION['user']['read_permission']) & intval($_SESSION['readperm'][$view])) > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * get_write_permission()
 * returns the write permission for a requested view
 * @param mixed $view
 * @return
 */
function get_write_permission($view) {
    if (!isset($_SESSION['writeperm'][$view])){
        set_permission_session();
    }

    if ((intval($_SESSION['user']['write_permission']) & intval($_SESSION['writeperm'][$view])) > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * set_permission_session()
 * fills the session with the read and write permissions
 * @return
 */
function set_permission_session(){
    $db = new db_function();
    $perm  = $db -> get_view_permissions();

    foreach($perm as $row){
        $_SESSION['readperm'][$row['view_name']] = $row['read_permission'];
        $_SESSION['writeperm'][$row['view_name']] = $row['write_permission'];
    }

    //views not in view list
    $special = array('statistic', 'imprint');
    foreach($special as $entry){
        $_SESSION['readperm'][$entry] = 1;
        $_SESSION['writeperm'][$entry] = 0;
    }
}

/**
 * check_role_access()
 *  returns true if the role is asigned to a permission value
 * @param mixed $role           the role, case senstive
 * @param mixed $permission     permission value
 * @return
 */
function check_role_access($role, $permission) {
    $roles = define_roles();
    $value = array_search($role, $roles);
    $value = pow(2,$value);

    if ((intval($permission) & intval($value))>0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * validdate()
 * checks if the string a date in the format yyyy-mm-dd or empty
 * @param mixed $datestring
 * @return
 */
function validdate($datestring) {
    $datestring = str_replace("'", "", $datestring);
    if ( $datestring == '0000-00-00'  || $datestring == '') {
        return true;
    }
    $test_arr  = explode('-', $datestring);
    if ((count($test_arr) == 3) && (is_numeric($test_arr[0]) && is_numeric($test_arr[1]) && is_numeric($test_arr[2]))) {
        if (checkdate(intval($test_arr[1]), intval($test_arr[2]), intval($test_arr[0]))) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * test_data()
 * provide test datat started in index.php
 * @return
 */
function test_data() {
    $_SESSION['user']['id'] = 1;
    $_SESSION['user']['name'] = 'norbert';
    $_SESSION['user']['email'] = 'norbert@cacert.org';
    $_SESSION['user']['read_permission'] = 15;
    $_SESSION['user']['write_permission'] = 14;
}

/**
 * write_log()
 * writes an entry to the log file
 * structure date, userid, itemid, content
 * @param mixed $file       suffix showing the type of log admin/user
 * @param mixed $id         itemid
 * @param mixed $content
 * @return
 */
function write_log($file, $id, $content) {
    $uid = 0;
    if ((isset($_SESSION['user']['id']))) {
        $uid = $_SESSION['user']['id'];
    }

    $filename ="../log/" . $file . "_" .  date('Y_m') . ".log";
    $f = fopen($filename, 'a');
    $output = date('Y_m_d_H_i_s') . ', ' . $uid . ', ' . $id . ' ,' . $content . "\r\n";
    fwrite($f, $output);
    fclose($f);
}

/**
 * create_url()
 * creates a URL matching the URL rewrite pattern
 * @param mixed $type       give the function
 * @param integer $secure   if not 0 add token secure to url
 * @param array $parameter  array of parameters e.g. 'id' =>'1'
 * @return
 */
function create_url($type, $secure = 0, $parameter = array()) {
    $base = '/index.php/';

    if ( $secure ) {
        $secureurl = 'secure/';
    } else {
        $secureurl = '';
    }

    $url = $base . $secureurl . $type;

    $arrlen = count($parameter);

    if ( $arrlen > 0 ) {
        $i = 1;
        $url .= '?';

        foreach($parameter as $key => $value) {
            $url .= urlencode($key) . '=' .  urlencode($value);

            if ($i < $arrlen ) {
                $url .= '&';
            }

            $i++;
        }
    }

    return $url;
}

/**
 * create_url_html()
 * creates a URL matching the URL rewrite pattern which is secured with htmlspecialchars()
 * @param mixed $type       give the function
 * @param integer $secure   if not 0 add token secure to url
 * @param array $parameter  array of parameters e.g. 'id' =>'1'
 * @return
 */
function create_url_html($type, $secure = 0, $parameter = array()) {
    return htmlspecialchars(create_url($type, $secure, $parameter));
}
