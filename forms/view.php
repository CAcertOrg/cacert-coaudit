<?php

include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';

$db = new db_function();

$roles = define_roles();

//Check access to page
$readperm = get_read_permission('view');
$writeperm = get_write_permission('view');

if (isset($_REQUEST['vid'])) {
    $vid = intval($_REQUEST['vid']);
} else {
    $vid = 0;
}

if ($vid == 0) {
    //new user
    $view_rigths_id = 0;
    $view_name = '';
    $read = 0;
    $write = 0;
    $active = '';
} else {
    //edit user
    $view = $db -> get_all_view($vid);
    $view_rigths_id = $view['view_rigths_id'];
    $view_name = $view['view_name'];
    $read = $view['read_permission'];
    $write = $view['write_permission'];
    $active = $view['active'];
}

//refresh user

$hidden[] = array('vid',$vid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo build_form_header(create_url('view', 1));
echo tableheader(_('View'), 3);
echo tablerow_3col_textbox_2col(_('Name of view'), 'view_name', $view_name);
echo tablerow_topics_active($active);
echo tablerow_user_rights($roles, $read, $write);
echo tablefooter_user(3, $vid, $writeperm);
echo build_form_footer($hidden);
echo end_div();
