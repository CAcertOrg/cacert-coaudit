<?php
include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
//Check access to page
$readperm = get_read_permision('viewlist');
$writeperm = get_write_permision('viewlist');


//get data
$views = get_all_view();


echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('View list'), 4);
echo tablerow_viewlist_header();


if (mysql_num_rows($views) <= 0 ) {
    echo tablerow_no_entry(4);
} else {
    while($view = mysql_fetch_assoc($views)){
        echo tablerow_viewlist($view);
    }
}

if ($writeperm > 0) {
    echo tablerow_viewlist_new();
}
echo end_div();


?>