<?php
include_once('../module/class.db_functions.php');


/**
 * tidystring()
 * cleans a string
 * @param mixed $input
 * @return
 */
function tidystring($input){
    if ($input != "") {
        return trim(mysql_real_escape_string(stripslashes($input)));
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
    $result[] = _('Coauditor'); // 2
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
function get_read_permission( $view){
    $db = new db_function();

    $view_perm = $db -> get_view_right($view);
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

/**
 * get_write_permission()
 * returns the write permission for a requested view
 * @param mixed $view
 * @return
 */
function get_write_permission( $view){
    $db = new db_function();

    $view_perm = $db -> get_view_right($view);
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

/**
 * validdate()
 * checks if the string a date in the format yyyy-mm-dd
 * @param mixed $datestring
 * @return
 */
function validdate($datestring){
    $test_arr  = explode('-', $datestring);
    if (count($test_arr) == 3) {
        if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
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
function test_data(){
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
function write_log($file, $id, $content){
    $filename ="../log/" . $file . "_" .  date('Y_m') . ".log";
    $f = fopen($filename, 'a');
    $output = date('Y_m_d_H_i_s') . ', ' . $_SESSION['user']['id'] . ', $id, ' . $content . '\r\n';
    fwrite($f, $output);
    fclose($f);
}
?>