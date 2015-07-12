<?php

$continue = true;
if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
    $read = 0;
    $write = 0;
    $vid = array_key_exists('vid',$_REQUEST) ? intval($_REQUEST['vid']) : '';
    $view_name = array_key_exists('view_name',$_REQUEST) ? tidystring($_REQUEST['view_name']) : '';

    for ($i = 0; $i <= $userroles; $i++){
        $readtest = array_key_exists('read'.$i,$_REQUEST) ? tidystring($_REQUEST['read'.$i]) : '';
        $writetest = array_key_exists('write'.$i,$_REQUEST) ? tidystring($_REQUEST['write'.$i]) : '';

        if ($readtest == "'on'") {
            $read +=  pow(2, $i);
        }

        if ($writetest == "'on'") {
            $write +=  pow(2, $i);
        }
    }

    $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
    if ($activetest == "'on'") {
        $active = 1;
    } else {
        $active = 0;
    }

    if (isset( $_REQUEST['new'])){
        $db->insert_view($view_name, $read, $write);
    } else {
        $db->update_view($view_name, $read, $write, $active, $vid);
    }

    include '../forms/viewlist.php';

    $continue = false;
}

if ($continue) {
    include '../forms/view.php';
}

?>