<?php
include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('viewlist');
$writeperm = get_write_permission('viewlist');


//get data
$views = $db -> get_all_view();


echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('View list'), 4);
echo tablerow_viewlist_header();


if (count($views) <= 0 ) {
    echo tablerow_no_entry(4);
} else {
    foreach ($views as $row) {
        echo tablerow_viewlist($row);
    }
}

if ($writeperm > 0) {
    echo tablerow_viewlist_new();
}
echo end_div();


?>