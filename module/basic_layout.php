<?php

function titlebar(){
    $tabstrings = '<div class="headerbar">';
    $tabstrings .= '<h1>Co-Audit system</h1>';
    $tabstrings .= '</div >';

    return $tabstrings;
}

function footer(){
    $tabstrings = '<div class="footerbar">';
    $tabstrings .= '<h3>Links to mmmm</h3>';
    $tabstrings .= '</div >';

    return $tabstrings;
}

function menu(){
    $tabstrings = '<div class="menubar">';
    $tabstrings .= '<ul>';
    $tabstrings .= '<li>Statics</li>';
    $tabstrings .=      '<ul>';
    $tabstrings .=      '<li>Overview</li>';
    $tabstrings .=      '</ul>';

    $tabstrings .= '<li>Coauditors</li>';
    $tabstrings .=      '<ul>';
    $tabstrings .=      '<li>Enter new entry</li>';
    $tabstrings .=      '<li>List own entries</li>';
    $tabstrings .=      '</ul>';

    $tabstrings .= '<li>Admin</li>';
    $tabstrings .=      '<ul>';
    $tabstrings .=      '<li><a href="index.php?type=userlist">List user</a></li>';
    $tabstrings .=      '<li><a href="index.php?type=sessionlist">List sessions</a></li>';
    $tabstrings .=      '<li><a href="index.php?type=topiclist">List topics</a></li>';
    $tabstrings .=      '</ul>';

    $tabstrings .= '<li>Login</li>';
    $tabstrings .= '<li>Logout</li>';
    $tabstrings .= '</ul>';
    $tabstrings .= '</div >';

    return $tabstrings;
}

function headerstart($title){
    $tabstrings = '<html >';
    $tabstrings .= '<head >';
    $tabstrings .= '<title>' . _('CAcert Coaudit'). $title . '</title>';
    $tabstrings .= '<link rel="stylesheet" href="coaudit.css" type="text/css" />';
    $tabstrings .= '</head >';
    $tabstrings .= '<body>';
    return $tabstrings;
}

function footerend(){
    $tabstrings = '</body >';
    $tabstrings .= '</html>';
    return $tabstrings;

}
?>