<?php

$continue = true;
if (isset( $_REQUEST['change'])) {
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
    if (strpos($primaryemail, '@') !== FALSE) {
        if (!filter_var($primaryemail, FILTER_VALIDATE_EMAIL)) {
            // invalid emailaddress
            $primaryemail == '';
            $error .= _('Missing or wrong email address') . '<br>';
        }
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
        if (isset( $_REQUEST['new'])) {
            $assurerid = $db->insert_result_user($primaryemail, $isassurer, $expierencepoints, $country, $location, $coauditdate);
            foreach($questions as $question){
                $db->insert_result_topic($question[0], $coaudit_session_id, $assurerid, $question[1], $question[2]);
            }
        } else {
            $db->update_result_user($primaryemail, $isassurer, $expierencepoints, $country, $location, $coauditdate, $rid);
            foreach($questions as $question){
                $db->update_result_topic($question[0], $coaudit_session_id, $rid, $question[1], $question[2]);
            }

            $_SESSION['user']['rid'] = 0;

            //$_SESSION['coauditor'] = array_key_exists('cid',$_REQUEST) ? $_REQUEST['cid'] : '';
            $_SESSION['coauditor'] = true;

            include '../forms/resultlist.php';
            exit;
        }

        $_SESSION['user']['coaudit_session'] = $coaudit_session_id;

        include '../forms/result.php';

        $continue = false;
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

if (isset( $_REQUEST['delete'])) {
    $db->delete_result($_REQUEST['rid'], $_SESSION['user']['coaudit_session']) ;

    include '../forms/resultlist.php';

    $_SESSION['user']['rid'] = 0;
    $continue = false;
}

if ($continue) {
    include '../forms/result.php';
}


?>