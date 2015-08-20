<?php

include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';

$db = new db_function();

$roles = define_roles();

//Check access to page
$readperm = get_read_permission('user');
$writeperm = get_write_permission('user');

if (isset($_REQUEST['cid'])) {
    $cid = intval($_REQUEST['cid']);
} else {
    $cid = 0;
}

if ($cid == 0) {
    //new user
    $userid = 0;
    $username = '';
    $usermail = '';
    $read = 0;
    $write = 0;
} else {
    //edit user
    $user = $db -> get_userdata($cid);
    $userid = $user['coauditor_id'];
    $username = $user['coauditor_name'];
    $usermail = $user['email'];
    $read = $user['read_permission'];
    $write = $user['write_permission'];
}

//refresh user

$hidden[] = array('cid',$userid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo build_form_header(create_url('user', 1));
echo tableheader(_('User'), 3);
echo tablerow_3col_textbox_2col(_('User'), 'username', $username);
echo tablerow_3col_textbox_2col(_('Email'), 'email', $usermail);
echo tablerow_user_rights($roles, $read, $write);
echo tablefooter_user(3, $userid, $writeperm);
echo build_form_footer($hidden);
echo end_div();
