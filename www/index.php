<?php
session_start();
include_once('../module/basic_layout.php');

include_once('../module/db_functions.php');

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
    if (isset( $_REQUEST['cid'])) {
         $_SESSION['user']['cid'] = $_REQUEST['cid'];
    }else{
         $_SESSION['user']['cid']=0;
    }

    include('../forms/user.php');
}

echo footerend();
?>


