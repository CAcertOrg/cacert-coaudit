<?php

function tidystring($input){
    return trim(mysql_real_escape_string(stripslashes($input)));
}

function define_roles(){
    $result = array();
    $result[] = _('Guest'); // 1
    $result[] = _('Coauditor'); // 2
    $result[] = _('Session administrator'); // 4
    $result[] = _('Administrator'); // 8
    $result[] = _('Auditor'); // 16
    return $result;
}
?>