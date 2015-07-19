<?php

if (isset( $_REQUEST['csid'])) {
    $_SESSION['user']['csid'] = $_REQUEST['csid'];

    if (isset( $_REQUEST['session_id'])) {
        $_SESSION['user']['csid'] =  $_REQUEST['session_id'];
    }

    include '../forms/statisticcountry.php';
} else {
    include '../forms/statistic.php';
}
