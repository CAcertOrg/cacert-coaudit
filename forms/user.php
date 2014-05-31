<?php
include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
$roles = define_roles();

//Check access to page
$readperm = get_read_permision('user');
$writeperm = get_write_permision('user');

if (isset($_REQUEST['cid'])) {
    $cid = intval($_REQUEST['cid']);
} else {
    $cid =0;
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
    $user = get_userdata($cid);
    $userid = $user['coauditid'];
    $username = $user['coauditor_name'];
    $usermail = $user['email'];
    $read = $user['read_permission'];
    $write = $user['write_permission'];
}

//refresh user



$hidden[]=array('cid',$userid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=user');
echo tableheader(_('User'), 3);
echo tablerow_3col_textbox_2col(_('User'), 'username', $username);
echo tablerow_3col_textbox_2col(_('Email'), 'email', $usermail);
echo tablerow_user_rights($roles, $read, $write);
echo tablefooter_user(3, $userid, $writeperm);
echo built_form_footer($hidden);
echo end_div();


?>