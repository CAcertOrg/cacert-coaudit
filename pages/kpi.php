<?php

$continue = true;
$missing = false;

if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
    $kid = array_key_exists('kid',$_REQUEST) ? intval($_REQUEST['kid']) : '';
    $session_year = array_key_exists('session_year',$_REQUEST) ? intval($_REQUEST['session_year']) : '';
    $assurances = array_key_exists('assurances',$_REQUEST) ? intval($_REQUEST['assurances']) : '';
    $target = array_key_exists('target',$_REQUEST) ? intval($_REQUEST['target']) : '';
    $session_id = array_key_exists('session_id',$_REQUEST) ? intval($_REQUEST['session_id']) : '';

    if ( !$session_year || !$assurances || !$target ||
    $session_year == 0 || $assurances == 0 || $target == 0 ) {

        //missing data
        echo error(_('Some data is missing or not a number.'));

        $missing = true;
    }

    if (!$missing) {
        if (isset( $_REQUEST['new']) && $missing == false){
            $db->insert_kpi($session_id, $session_year, $assurances, $target);
        } else {
            $db->update_kpi($session_id, $session_year, $assurances, $target, $kid);
        }

        include '../forms/kpilist.php';

        $continue = false;
    }
}

if ($continue) {
    include '../forms/kpi.php';
}

?>