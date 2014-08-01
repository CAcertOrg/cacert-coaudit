<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('result');
$writeperm = get_write_permission('result');

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


$hidden[] = array('rid', $rid);

$sessionres = $db -> get_all_session();
$coaudit_session_id = 2; //get last session
//buildform
echo start_div('content');

//get data
$questions = $db -> get_all_sessiontopics(' `st`.`coaudit_session_id` = ' . $coaudit_session_id . ' and `st`.`active` = 1');

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
foreach($questions as $row){
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