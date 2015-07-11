<?php

include_once '../module/output_functions.php';
include_once '../module/class.db_functions.php';

$db = new db_function();

//Check access to page
$readperm = get_read_permission('kpilist');
$writeperm = get_write_permission('kpilist');

//get data
$kpis = $db -> get_all_kpi();

echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('KPI list'), 4);
echo tablerow_kpilist_header();

if (count($kpis) <= 0 ) {
    echo tablerow_no_entry(2);
} else {
    foreach ($kpis as $kpi) {
        echo tablerow_kpilist($kpi);
    }
}

if ($writeperm > 0) {
    echo tablerow_kpilist_new();
}

echo end_div();
