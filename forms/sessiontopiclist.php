<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();
//Check access to page
$readperm = get_read_permission('sessiontopiclist');
$writeperm = get_write_permission('sessiontopiclist');
$sid = 0;
if (isset( $_REQUEST['sid'])) {
    $sid = $_REQUEST['sid'];
}else{
    echo error(_('You do not have the right to read this page.'));
    exit;}
//get data
$sessiontopics = $db -> get_all_sessiontopics(' `s`.`session_id` = ' . $sid);


echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('Session topics list'), 4);
echo tablerow_sessiontopiclist_header();

if (count($sessiontopics) <= 0 ) {
    echo tablerow_no_entry(4);
} else {
    foreach($sessiontopics as $sessiontopic){
        echo tablerow_sessiontopicsslist($sessiontopic);
    }
}

if ($writeperm > 0) {
    echo tablerow_sessionstopicslist_new();
}
echo end_div();



?>