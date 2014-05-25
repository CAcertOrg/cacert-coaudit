<?php

function tidystring($input){
    return trim(mysql_real_escape_string(stripslashes($input)));
}

?>