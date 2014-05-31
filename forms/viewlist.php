<?php
include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page


//get data
$views = get_all_view();


echo start_div('content');
echo tableheader(_('View list'), 4);
echo tablerow_viewlist_header();


if (mysql_num_rows($views) <= 0 ) {
    echo tablerow_no_entry(4);
} else {
    while($view = mysql_fetch_assoc($views)){
        echo tablerow_viewlist($view);
    }
}

//echo tablerow_userlist($user);

echo tablerow_viewlist_new();
echo end_div();


?>