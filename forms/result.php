<?php

include_once('../module/output_functions.php');
include_once('../module/db_functions.php');

//Check access to page
$readperm = get_read_permision('result');
$writeperm = get_write_permision('result');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

$primaryemail = '';
$isassurer = '';
$expierencepoints = '';
$country = '';
$location = '';
$coauditdate = '';

$rid = 0;
/*
if (isset($_REQUEST['rid'])) {
    $sid = intval($_REQUEST['rid']);
} else {
    $sid =0;
}
*/

/*
if ($sid == 0) {
    //new session
    $session_id = 0;
    $session_name = '';
    $from = '';
    $to = '';
    $active = 0;
} else {
    //edit session
    $sessions = get_all_session($sid);
    $session_id = $sessions['session_id'];
    $session_name = $sessions['session_name'];
    $from = $sessions['from'];
    $to = $sessions['to'];
    $active = $sessions['active'];
}
*/


$hidden[] = array('rid', $rid);

$sessionres = get_all_session();
$coaudit_session_id = 2; //get last session
//buildform
echo start_div('content');

/*
if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}
*/

/*
`cacertuser_id` bigint(20) NOT NULL AUTO_INCREMENT,
`primaryemail` text NOT NULL,
`webdb_account_id` bigint(20) DEFAULT NULL,
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `assurer` tinyint(1) NOT NULL,
        `expierencepoints` int(11) NOT NULL,
        `country` varchar(2) NOT NULL,
        `created_by` int(11) NOT NULL,
        `location` text NOT NULL,
        `coauditdate` date NOT NULL,
        `active` int(11) NOT NULL,
        `deleted` timestamp NULL DEFAULT NULL,
`deleted_by` int(11) NOT NULL,

            `result_id` bigint(20) NOT NULL AUTO_INCREMENT,
            `session_topic_id` int(11) NOT NULL,
            `coauditsession_id` int(11) NOT NULL,
            `cacertuser_id` bigint(20) NOT NULL,
            `coauditor_id` int(11) NOT NULL,
            `result` tinyint(1) NOT NULL,
            `comment` text,
            `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `active` int(11) NOT NULL,
                `deleted` timestamp NULL DEFAULT NULL,
`deleted_by` int(11) NOT NULL,
*/

//get data
$questions = get_all_sessiontopics(' `st`.`coaudit_session_id` = ' . $coaudit_session_id . ' and `st`.`active` = 1');

echo built_form_header('../www/index.php?type=result');
echo tableheader(_('Enter coaudit result'), 2);
echo tablerow_2col_dropbox(_('Coaudit session'), $sessionres, $coaudit_session_id, 'session_id', 'session_name', 0);
echo tablerow_2col_textbox(_('Assurer mail'), 'primaryemail', $primaryemail);
echo tablerow_2col_checkbox(_('Is assuerer?'), 'assurer', $isassurer);
echo tablerow_2col_textbox(_('Expierence Points'), 'expierencepoints', $expierencepoints);
echo tablerow_2col_textbox(_('Residence country (ISO 2 letter code)'), 'country', $country);
echo tablerow_2col_textbox(_('Location/Event'), 'location', $location);
echo tablerow_2col_textbox(_('Date of coaudit'), 'coauditdate', $coauditdate);

$i = 1;
while($row = mysql_fetch_assoc($questions)){
    echo tablerow_label('Q' . $row['topic_no'] . ' ' .$row['session_topic'], 2);
    echo tablerow_2col_checkbox(_('Result (passed - tick)'), 'r'. $i, '');
    echo tablerow_2col_textbox(_('Comment'), 'c'. $i,  '');
    $hidden[]=array('qid' . $i, $row['session_topic_id']);
    $i +=1;
}
echo tablefooter_user(2, $rid, $writeperm);;
echo built_form_footer($hidden);
echo end_div();


?>