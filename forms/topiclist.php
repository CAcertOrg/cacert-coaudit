<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page


//get data
$topics = get_all_topics();


echo start_div('content');
echo tableheader(_('Topics list'), 3);
echo tablerow_topicslist_header();


if (mysql_num_rows($topics) <= 0 ) {
    echo tablerow_no_entry(2);
} else {
    while($topic = mysql_fetch_assoc($topics)){
        echo tablerow_topicslist($topic);
    }
}

//echo tablerow_userlist($user);

echo tablerow_topicslist_new();
echo end_div();


?>