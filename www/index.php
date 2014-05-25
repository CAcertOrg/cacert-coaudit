<?php
session_start();
include_once('../module/basic_layout.php');
include_once('../module/basic_functions.php');
include_once('../module/db_functions.php');

$_SESSION['user']['id']=1;
dbstart();

if (isset( $_REQUEST['type'])) {
    $type = $_REQUEST['type'];
}else{
    $type='';
}
echo headerstart();

echo titlebar();

echo menu();

echo footer();

if ($type == 'userlist') {
    include('../forms/userlist.php');
}

if ($type == 'user') {
    $continue=true;
    if (isset( $_REQUEST['new']) | isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $cid = array_key_exists('cid',$_REQUEST) ? intval($_REQUEST['cid']) : '';
        $username = array_key_exists('username',$_REQUEST) ? tidystring($_REQUEST['username']) : '';
        $email = array_key_exists('email',$_REQUEST) ? tidystring($_REQUEST['email']) : '';
        for ($i = 0; $i <= 3; $i++){
            $readtest = array_key_exists('read'.$i,$_REQUEST) ? tidystring($_REQUEST['read'.$i]) : '';
            $writetest = array_key_exists('write'.$i,$_REQUEST) ? tidystring($_REQUEST['write'.$i]) : '';
            if ($readtest == 'on') {
                $read +=  pow(2, $i);
            }
            if ($writetest == 'on') {
                $write +=  pow(2, $i);
            }
        }
        if (isset( $_REQUEST['new'])){
            insert_user($username, $email, $read, $write, intval($_SESSION['user']['id']));
        } else {
            update_user($username, $email, $read, $write, intval($_SESSION['user']['id']), $cid);
        }
        include('../forms/userlist.php');
        $continue=false;
    }
    if (isset( $_REQUEST['cid'])) {
         $_SESSION['user']['cid'] = $_REQUEST['cid'];
    }else{
         $_SESSION['user']['cid']=0;
    }
    if ($continue==true) {
        include('../forms/user.php');
    }
}

echo footerend();
?>


