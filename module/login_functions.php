<?php

include_once('class.db_functions.php');

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

function get_valid_email_from_cert(){

    $result = array();

    $i = 0;
    $test = 'SSL_CLIENT_S_DN_Email';
    $testaddress = $test;
    while (isset($_SERVER[ $testaddress ])) {
        if (strpos($_SERVER[ $testaddress ], '@cacert.org') > 0) {
            $result[] = $_SERVER[ $testaddress ];
        }
        $i += 1;
        $testaddress  = $test . '_' . $i;
    }
    return $result;
}

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


function login(){

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

    $result = get_userdata($uid);
    if (isset($result)) {
       $_SESSION['error'] =_('It is not possilble to log you in. May be there is a problem with your user account.');
        return false;
    }

    $_SESSION['user']['id'] = $uid;
    $_SESSION['user']['name'] = $result['name'];
    $_SESSION['user']['email'] = $result['email'];
    $_SESSION['user']['read_permission'] = $result['read_permission'];
    $_SESSION['user']['write_permission'] = $result['write_permission'];
    return true;


}

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

?>