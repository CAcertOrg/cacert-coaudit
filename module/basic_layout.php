<?php

function titlebar(){
    $coauditsystem = _('Co-Audit system');
    $tabstrings = <<<foohtmlheader
    <header class="mainHeader">
    <div class="headerbar">
    <img class="logo" src="res/img/CAcert-logo-colour-210.png"/>
    <h1>$coauditsystem</h1>
    </div>
foohtmlheader;

    return $tabstrings;
}

function footer(){
    $tabstrings = <<<foohtmlfooter
        <div class="footerbar">
            <a href="#">Data Protection</a>
            <a href="index.php?type=imprint">Imprint</a>
        </div>
foohtmlfooter;

    return $tabstrings;
}

function menu(){
    $statistics = _('Statistics');
    $overview = _('Overview');
    $coauditors = _('Co-Auditors');
    $newEntry = _('Enter new entry');
    $ownEntry = _('List own entries');
    $admin = _('Administrative view');
    $adminuser = _('List user');
    $adminsession = _('List sessions');
    $admintopic = _('List topics');
    $adminresult = _('List results');
    $adminview = _('List views');
    $login = _('Login');
    $logout = _('Logout');
    $username = array_key_exists('name', $_SESSION['user']) ? ' [' . tidystring( $_SESSION['user']['name']) . ']': '';
    //$Admin

    $tabstrings1 = <<<foohtmlnav1
		<nav>
		<!--div class="menubar"-->
		    <ul>
			<li class="cat1">
				<a href="#">$statistics</a>
			    <ul>
				<li><a href="index.php?type=statistic">$overview</a></li>
			    </ul>
			</li>
foohtmlnav1;
    $tabstrings2 = <<<foohtmlnav2
			<li class="cat2"><a href="#">$coauditors</a>
			    <ul>
				<li><a href="index.php?type=result">$newEntry</a></li>
				<li><a href="index.php?type=resultlist&cid=true">$ownEntry</a></li>
			    </ul>
			</li>
foohtmlnav2;
    $tabstrings3 = <<<foohtmlnav3
			<li class="cat3"><a href="#"><span>$admin</span></a>
			    <ul class="sub-menu">
				<li><a href="index.php?type=userlist">$adminuser</a></li>
				<li><a href="index.php?type=sessionlist">$adminsession</a></li>
				<li><a href="index.php?type=topiclist">$admintopic</a></li>
				<li><a href="index.php?type=resultlist">$adminresult</a></li>
				<li><a href="index.php?type=viewlist">$adminview</a></li>
			    </ul>
			</li>
foohtmlnav3;
    $tabstrings4 = <<<foohtmlnav4
			<li class="cat4"><a href="index.php?login=login">$login</a></li>
foohtmlnav4;
    $tabstrings5 = <<<foohtmlnav5
			<li class="cat5"><a href="index.php?login=logout">$logout $username</a></li>
foohtmlnav5;
    $tabstrings6 = <<<foohtmlnav6
		    </ul>
			<!--/div-->
		</nav>
foohtmlnav6;



if ($_SESSION['user']['read_permission'] == 1) {
    $tabstrings2 = '';
    $tabstrings3 = '';
    $tabstrings5 = '';
} else {
    if (get_read_permission('adminmenue') == 0){
        $tabstrings3 = '';
    }
    $tabstrings4 = '';
}




    return $tabstrings1 . $tabstrings2 . $tabstrings3 . $tabstrings4 . $tabstrings5 . $tabstrings6 ;
}

function headerstart($title){
    $tabstrings = '<!DOCTYPE html>';
    $tabstrings .= '<html lang="en">';
    $tabstrings .= '<head>';
    $tabstrings .= '<title>' . _('CAcert Coaudit'). $title . '</title>';
    $tabstrings .= '<link rel="stylesheet" href="res/css/template.css" type="text/css" />';
    $tabstrings .= '<meta name=content="width=device=width, initial-scale=1.0"/>';
    $tabstrings .= '</head>';
    $tabstrings .= '<body class="body">';
    return $tabstrings;
}

function footerend(){
    $tabstrings = '</body>';
    $tabstrings .= '</html>';
    return $tabstrings;

}
?>
