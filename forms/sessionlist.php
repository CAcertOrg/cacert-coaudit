<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page
$readperm = get_read_permision('user');
$writeperm = get_write_permision('user');


//get data
$sessions = get_all_session();

echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('Session list'), 5);
echo tablerow_userlist_header();


if (mysql_num_rows($sessions) <= 0 ) {
    echo tablerow_no_entry(5);
} else {
    while($session = mysql_fetch_assoc($sessions)){
        echo tablerow_sessionslist($session);
    }
}

//echo tablerow_userlist($user);

if ($writeperm > 0) {
    echo tablerow_sessionslist_new();
}
echo end_div();


?>