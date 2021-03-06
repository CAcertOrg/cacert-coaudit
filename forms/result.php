<?php

include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';

$db = new db_function();

//Check access to page
$readperm = get_read_permission('result');
$writeperm = get_write_permission('result');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

$rid = 0;
if (isset($_SESSION['user']['rid'])) {
    if (intval($_SESSION['user']['rid']) > 0) {
        $rid = intval($_SESSION['user']['rid']);
        $writeperm = 0;
    }
} else {
    $rid = 0;
}

if (check_role_access('Auditor', $_SESSION['user']['write_permission'])) {
    $writeperm = 2;
    if (0 == $rid) {
        $writeperm = 0;
        echo error(_('Auditors are not allowed to add new results'));
        exit;
    }
}

if ($rid == 0) {
    $primaryemail = '';
    $isassurer = '';
    $expierencepoints = '';
    $country = '';
    $location = '';
    $coauditdate = '';

    //get last session
    if (array_key_exists('coaudit_session',$_SESSION['user'])) {
        $coaudit_session_id = $_SESSION['user']['coaudit_session'];
    } else {
        $cs_res= $db -> get_all_session('WHERE `default` = 1');
        $coaudit_session_id = $cs_res['session_id'];
    }

    //get data
    $questions = $db -> get_all_sessiontopics(' `st`.`coaudit_session_id` = ' . $coaudit_session_id . ' and `st`.`active` = 1');
} else {
    $user = $db->get_result_user($rid);
    $primaryemail = $user['primaryemail'];
    $isassurer = $user['assurer'];
    $expierencepoints = $user['expierencepoints'];
    $country = $user['country'];
    $location = $user['location'];
    $coauditdate = $user['coauditdate'];

    //change entry within 24 h
    if (date("Y.m.d h:m:s", time() - 86400) < date("Y.m.d h:m:s", strtotime($user['created']))) {
        if (0 ==  $writeperm ) {
            $writeperm = 1;
        }
    }

    $coaudit_session_id = $_SESSION['user']['coaudit_session'];
    $questions = $db->get_result_topics($coaudit_session_id,$rid);
}


$checkno = $db -> check_no_sessiontopics($coaudit_session_id);

$hidden[] = array('rid', $rid);

$sessionres = $db->get_all_session();

//buildform
echo start_div('content');

if ($assurerid > 0) {
    echo last_id_entered($assurerid);
}

echo build_form_header(create_url('result', 1));
echo tableheader(_('Enter RA-Audit result'), 2);
echo tablerow_2col_dropbox_apply(_('RA-Audit session'), $sessionres, $coaudit_session_id, 'session_id', 'session_name', 'change', _('Update'), 0);

echo build_just_form_footer();
echo build_form_header(create_url('result', 1));
$hidden[] = array('session_id',  $coaudit_session_id);
foreach($checkno as $entry){
    $writeperm = 0;
    echo error(sprintf(_('There is a problem with definition of the session.%s Get in contact with the administrator.'), '</br>'));
}

echo tablerow_2col_textbox(_('Assurer mail *'), 'primaryemail', $primaryemail, 1);
echo tablerow_2col_checkbox(_('Is Assurer?'), 'assurer', $isassurer);
echo tablerow_2col_textbox(_('Experience Points *'), 'expierencepoints', $expierencepoints , 1);
echo tablerow_2col_textbox(_('Residence country (ISO 2 letter code) *'), 'country', $country , 1);
echo tablerow_2col_textbox(_('Location/Event *'), 'location', $location, 1);
echo tablerow_2col_textbox(_('Date of RA-Audit (YYYY-MM-DD) *'), 'coauditdate', $coauditdate, 1);

$i = 1;
foreach($questions as $row){
    $r = '';
    $c = '';

    if ($rid !=0) {
        $r = intval($row['result']);
        $c = $row['comment'];
    }

    echo tablerow_label('Q' . $row['topic_no'] . ' ' .$row['session_topic'], 2);
    echo tablerow_2col_checkbox(_('Result (passed - tick)'), 'r'. $i, $r);
    echo tablerow_2col_textbox(_('Comment'), 'c'. $i, $c);

    $hidden[] = array('qid' . $i, $row['session_topic_id']);

    $i +=1;
}

echo tablefooter_user(2, $rid, $writeperm);
echo build_form_footer($hidden);
echo end_div();
