<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();
//Check access to page
$readperm = get_read_permission('userlist');
$writeperm = get_write_permission('userlist');


//get data
$users = $db->get_all_user();
//$user=array(1,'dddd',"www",2,3);

echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('User list'), 3);
echo tablerow_userlist_header();

if (count($users) <= 0 ) {
    echo tablerow_no_entry(2);
} else {
    foreach($users as $user){
        echo tablerow_userlist($user);
    }
}

//echo tablerow_userlist($user);
if ($writeperm > 0) {
    echo tablerow_userlist_new();
}
echo end_div();


?>