<?php

include_once('class.db_functions.php');

/**
 * check_cert()
 * checks if a lcient certificate is valid for login
 * @return
 */
function check_cert(){
    $emails = array();

    if (!isset($_SERVER['SSL_CLIENT_VERIFY'])) {
         return false;
    }
    //certificate given
    if ($_SERVER['SSL_CLIENT_VERIFY'] != 'SUCCESS') {
        return false;
    }

    //certificate valid
    if ($_SERVER['SSL_CLIENT_V_REMAIN'] <= 0) {
        return false;
    }

    //cert issued by CAcert
    switch ($_SERVER['SSL_CLIENT_I_DN']) {
        case '/O=Root CA/OU=http://www.cacert.org/CN=CA Cert Signing Authority/emailAddress=support@cacert.org':
        case '/O=CAcert Inc./OU=http://www.CAcert.org/CN=CAcert Class 3 Root':
            ;
            break;
        default:
            return false;
    } // switch

    //check email address

    $emails = get_valid_email_from_cert();
    if (isset($email)) {
        if (count($mails) == 0) {
            return FALSE;
        }
    }

    return true;
}

/**
 * get_valid_email_from_cert()
 * returns an array with all email address of a client certificate with *@cacert.org
 * @return
 */
function get_valid_email_from_cert(){
    $result = array();

    $test =  $_SERVER['SSL_CLIENT_S_DN'];
    $testaddress = explode("/", $test);

    foreach ($testaddress as $address) {
        $addresstest = explode("=", $address);
        if ($addresstest[0] == "emailAddress") {
            if (strpos($addresstest[1], '@cacert.org') > 0) {
                $result[] = $addresstest[1];
            }
        }
    }

    return $result;
}

/**
 * get_uid()
 * returns the uid for a given email address
 * @param mixed $emails
 * @return
 */
function get_uid($emails){
$db = new db_function();
    foreach ($emails as $email) {
	$uid = $db -> get_userid_from_mail($email);
        if ($uid > 0) {
            return $uid;
        }
    }
    return 0;
}

/**
 * login()
 * returns if a client cert login was successful or not
 * @return
 */
function login(){
$db = new db_function();
    if (check_cert() === false) {
        $_SESSION['error'] =_('It is not possilble to log you in. May be there is a problem with the certificate linked to your user account.');
        return false;
    }

    $emails = get_valid_email_from_cert();

    $uid = get_uid($emails);
    if ($uid == 0) {
        $_SESSION['error'] =_('It is not possilble to log you in. May be there is a problem with your user account.');
        return false;
    }

    $result = $db -> get_userdata($uid);
    if (!isset($result)) {
       $_SESSION['error'] =_('It is not possilble to log you in. May be there is a problem with your user account.');
        return false;
    }

    $_SESSION['user']['id'] = $uid;
    $_SESSION['user']['name'] = $result['coauditor_name'];
    $_SESSION['user']['email'] = $result['email'];
    $_SESSION['user']['read_permission'] = $result['read_permission'];
    $_SESSION['user']['write_permission'] = $result['write_permission'];
    return true;


}

/**
 * logout()
 * used for logout from the system
 * @return
 */
function logout(){
/*
    $_SESSION['user']['id'] = '';
    $_SESSION['user']['email'] = '';
    $_SESSION['user']['name'] = '';
    $_SESSION['user']['read_permission'] = '';
    $_SESSION['user']['write_permission'] = '';
*/
    session_destroy();
}