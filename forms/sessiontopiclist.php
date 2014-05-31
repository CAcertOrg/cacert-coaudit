<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page
$readperm = get_read_permision('sessiontopiclist');
$writeperm = get_write_permision('sessiontopiclist');


//get data
$sessiontopics = get_all_sessiontopics();


echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('Session topics list'), 4);
echo tablerow_sessiontopiclist_header();

if (mysql_num_rows($sessiontopics) <= 0 ) {
    echo tablerow_no_entry(4);
} else {
    while($sessiontopic = mysql_fetch_assoc($sessiontopics)){
        echo tablerow_sessiontopicsslist($sessiontopic);
    }
}

if ($writeperm > 0) {
    echo tablerow_sessionstopicslist_new();
}
echo end_div();



?>