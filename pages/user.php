<?php

$continue = true;
if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
    $read = 0;
    $write = 0;
    $cid = array_key_exists('cid',$_REQUEST) ? intval($_REQUEST['cid']) : '';
    $username = array_key_exists('username',$_REQUEST) ? tidystring($_REQUEST['username']) : '';
    $email = array_key_exists('email',$_REQUEST) ? tidystring($_REQUEST['email']) : '';
    for ($i = 0; $i <= $userroles; $i++) {
        $readtest = array_key_exists('read'.$i,$_REQUEST) ? tidystring($_REQUEST['read'.$i]) : '';
        $writetest = array_key_exists('write'.$i,$_REQUEST) ? tidystring($_REQUEST['write'.$i]) : '';

        if ($readtest == "'on'") {
            $read += pow(2, $i);
        }

        if ($writetest == "'on'") {
            $write += pow(2, $i);
        }
    }

    //check valid data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email == '';
    }

    if ($username == '' || $email == '') {
        //missing data
    }

    if (isset( $_REQUEST['new'])){
        $db->insert_user($username, $email, $read, $write, intval($_SESSION['user']['id']));
    } else {
        $db->update_user($username, $email, $read, $write, intval($_SESSION['user']['id']), $cid);
    }

    include '../forms/userlist.php';
    //      http_redirect("index.php", array("type" => "userlist"), true, HTTP_REDIRECT_PERM);

    $continue = false;
}

if (isset( $_REQUEST['cid'])) {
    $_SESSION['user']['cid'] = $_REQUEST['cid'];
} else {
    $_SESSION['user']['cid']=0;
}

if ($continue) {
    include '../forms/user.php';
}

?>