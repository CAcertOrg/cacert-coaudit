<?php

include_once('db_functions.php');

function check_cert(){
    $emails = array();

    //certificate given
    if ($_SERVER['SSL_CLIENT_VERIFY'] != SUCCES) {
        return false;
    }

    //certificate valid
    if ($_SERVER['SSL_CLIENT_V_REMAIN'] <= 0) {
        return false;
    }

    //cert issued by CAcert
    switch ($_SERVER['SSL_CLIENT_I_DN']) {
        case 'O=CAcert Inc.,OU=http://www.CAcert.org,CN=CAcert Class 3 Root':
        case 'O=CAcert Inc.,OU=http://www.CAcert.org,CN=CAcert Class 3 Root':
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
    $result = arr();

    $i = 0;
    $test = 'SSL_CLIENT_S_AN';

    while (isset($_SERVER[$test.$i])) {
        if (strpos($_SERVER[$test.$i], '@cacert.org') > 0) {
            $pos = strpos($_SERVER[$test.$i], ':');
            $result[] = substr($_SERVER[$test.$i], $pos);
        }
        $i += 1;
    }
    return $result;
}

function get_uid($emails){
    foreach ($emails as $email) {
        $uid = get_userid_from_mail($email);
        if ($uid > 0) {
            return $uid;
        }
    }
    return 0;
}


function login(){

    if (check_cert() === false) {
        printf(_('It is not possilble to log you in. May be there is a problem with the certificate link to your user account.'));
        return false;
    }

    $emails = get_valid_email_from_cert();

    $uid = get_uid($emails);
    if ($uid == 0) {
        printf(_('It is not possilble to log you in. May be there is a problem with your user account.'));
        return false;
    }

    $result = get_userdata($uid);
    if (isset($result)) {
        printf(_('It is not possilble to log you in. May be there is a problem with your user account.'));
        return false;
    }

    $_SESSION['user']['id'] = $uid;
    $_SESSION['user']['name'] = $result['name'];
    $_SESSION['user']['email'] = $result['email'];
    $_SESSION['user']['name'] = $result['name'];
    $_SESSION['user']['read_permission'] = $result['read_permission'];
    $_SESSION['user']['write_permission'] = $result['write_permission'];
    return true;


}

function logout(){
    $_SESSION['user']['name'] = '';
    $_SESSION['user']['email'] = '';
    $_SESSION['user']['name'] = '';
    $_SESSION['user']['read_permission'] = '';
    $_SESSION['user']['write_permission'] = '';

}

?>