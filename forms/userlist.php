<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page


//get data
$users = get_all_user();
//$user=array(1,'dddd',"www",2,3);

echo start_div('content');
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

echo tablerow_userlist_new();
echo end_div();


?>