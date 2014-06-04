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
    $tabstrings = '';
    /*
    $tabstrings = '<div class="footerbar">';
    $tabstrings .= '<h3>Links to mmmm</h3>';
    $tabstrings .= '</div >';
    */
    return $tabstrings;
}

function menu(){
    $statics = _('Statics');
    $overview = _('Overview');
    $coauditors = _('Co-Auditors');
    $newEntry = _('Enter new entry');
    $ownEntry = _('List own entries');
    //$Admin

    $tabstrings = <<<foohtmlnav
		<nav>
		<!--div class="menubar"-->
		    <ul>
			<li class="cat1">
				<a href="#">$statics</a>
			    <ul>
				<li><a href="index.php?type=statistic">$overview</a></li>
			    </ul>
			</li>

			<li class="cat2"><a href="#">$coauditors</a>
			    <ul>
				<li><a href="index.php?type=result">$newEntry</a></li>
				<li><a href="#">$ownEntry</a></li>
			    </ul>
			</li>

			<li class="cat3"><a href="#"><span>Admin</span></a>
			    <ul class="sub-menu">
				<li><a href="index.php?type=userlist">List user</a></li>
				<li><a href="index.php?type=sessionlist">List sessions</a></li>
				<li><a href="index.php?type=topiclist">List topics</a></li>
			    </ul>
			</li>

			<li class="cat4"><a href="#">Login</a></li>

			<li class="cat5"><a href="#">Logout</a></li>
		    </ul>
			<!--/div-->
		</nav>
foohtmlnav;


/*
    $tabstrings = '<nav>';
    //$tabstrings = '<div class="menubar">';
    $tabstrings .= '<ul>';
    $tabstrings .= '<li class="cat1">' . _('Statics');
    $tabstrings .=      '<ul>';
    $tabstrings .=      '<li><a href=" ">' . _('Overview') . '</a></li>';
    $tabstrings .=      '</ul></li>';

    $tabstrings .= '<li>' . _('Coauditors') . '</li>';
    $tabstrings .=      '<ul>';
    $tabstrings .=      '<li><a href="index.php?type=result">' . _('Enter new entry') . '</a></li>';
    $tabstrings .=      '<li><a href="#">' . _('List own entries') . '</a></li>';
    $tabstrings .=      '</ul>';

    $tabstrings .= '<li>Admin</li>';
    $tabstrings .=      '<ul>';
    $tabstrings .=      '<li><a href="index.php?type=userlist">' . _('List user') . '</a></li>';
    $tabstrings .=      '<li><a href="index.php?type=sessionlist">' . _('List sessions') . '</a></li>';
    $tabstrings .=      '<li><a href="index.php?type=topiclist">' . _('List topics') . '</a></li>';
    $tabstrings .=      '<li><a href="index.php?type=viewlist">' . _('View administration') . '</a></li>';
    $tabstrings .=      '</ul>';

    $tabstrings .= '<li><a href="#">' . _('Login') . '</a></li>';
    $tabstrings .= '<li><a href="#">' . _('Logout') . '</a></li>';
    $tabstrings .= '</ul>';
    $tabstrings .= '</nav>';
    //$tabstrings .= '</div >';
*/
    return $tabstrings;
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
