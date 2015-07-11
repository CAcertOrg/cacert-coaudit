<?php

include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';

$db = new db_function();

//Check access to page
$readperm = get_read_permission('topiclist');
$writeperm = get_write_permission('topiclist');

//get data
$topics = $db->get_all_topics();

echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('Topics list'), 3);
echo tablerow_topicslist_header();

if (count($topics) <= 0 ) {
    echo tablerow_no_entry(2);
} else {
    foreach ($topics as $topic) {
        echo tablerow_topicslist($topic);
    }
}

//echo tablerow_userlist($user);
if ($writeperm > 0) {
    echo tablerow_topicslist_new();
}

echo end_div();
