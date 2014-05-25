<?php
include_once('../module/output_functions.php');
include_once('../module/db_functions.php');
$roles = array('Guest','Coauditor', 'Admin', 'Auditor');

//Check access to page
/*
if (isset($_SESSION['user']['cid'])) {
    $cid = intval($_SESSION['user']['cid']);
} else {
    $cid =0;
}
*/

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
echo built_form_header('../www/index.php?type=user');
echo tableheader('user', 3);
echo tablerow_3col_textbox_2col('User', 'username', $username);
echo tablerow_3col_textbox_2col('Email', 'email', $usermail);
echo tablerow_user_rights($roles, $read, $write);
echo tablefooter_user(3, $userid);
echo built_form_footer($hidden);
echo end_div();

?>

