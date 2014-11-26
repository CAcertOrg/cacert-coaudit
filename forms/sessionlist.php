<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('sessionlist');
$writeperm = get_write_permission('sessionlist');


//get data
$sessions = $db -> get_all_session();

echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('Session list'), 6);
echo tablerow_sessionslist_header();


if (count($sessions) <= 0 ) {
    echo tablerow_no_entry(6);
} else {
    foreach($sessions as $session){
        echo tablerow_sessionslist($session);
    }
}

//echo tablerow_userlist($user);

if ($writeperm > 0) {
    echo tablerow_sessionslist_new();
}
echo end_div();


?>