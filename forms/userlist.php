<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page
$readperm = get_read_permision('userlist');
$writeperm = get_write_permision('userlist');


//get data
$users = get_all_user();
//$user=array(1,'dddd',"www",2,3);

echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('User list'), 3);
echo tablerow_userlist_header();


if (mysql_num_rows($users) <= 0 ) {
    echo tablerow_no_entry(2);
} else {
    while($user = mysql_fetch_assoc($users)){
        echo tablerow_userlist($user);
    }
}

//echo tablerow_userlist($user);
if ($writeperm > 0) {
    echo tablerow_userlist_new();
}
echo end_div();


?>