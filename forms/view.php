<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
$roles = define_roles();

//Check access to page
$readperm = get_read_permision('view');
$writeperm = get_write_permision('view');
/*
   if (isset($_SESSION['user']['cid'])) {
   $cid = intval($_SESSION['user']['cid']);
   } else {
   $cid =0;
   }
*/

if (isset($_REQUEST['vid'])) {
    $vid = intval($_REQUEST['vid']);
} else {
    $vid =0;
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
    $view = get_all_view($vid);
    $view_rigths_id = $view['view_rigths_id'];
    $view_name = $view['view_name'];
    $read = $view['read_permission'];
    $write = $view['write_permission'];
    $active = $view['active'];
}

//refresh user



$hidden[]=array('vid',$vid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=view');
echo tableheader(_('View'), 3);
echo tablerow_3col_textbox_2col(_('Name of view'), 'view_name', $view_name);
echo tablerow_topics_active($active);
echo tablerow_user_rights($roles, $read, $write);
echo tablefooter_user(3, $vid, $writeperm);
echo built_form_footer($hidden);
echo end_div();

?>