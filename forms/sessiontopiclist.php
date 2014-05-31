<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page


//get data
$sessiontopics = get_all_sessiontopics();


echo start_div('content');
echo tableheader(_('Session topics list'), 4);
echo tablerow_sessiontopiclist_header();


if (mysql_num_rows($sessiontopics) <= 0 ) {
    echo tablerow_no_entry(4);
} else {
    while($sessiontopic = mysql_fetch_assoc($sessiontopics)){
        echo tablerow_sessiontopicsslist($sessiontopic);
    }
}


echo tablerow_sessionstopicslist_new();
echo end_div();



?>