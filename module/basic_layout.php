<?php

include_once 'basic_functions.php';
include_once 'class.db_functions.php';


/**
 * titlebar()
 * returns the title bar for a page
 * @return
 */
function titlebar() {
    $coauditsystem = _('RA-Audit system');
    $tabstrings = <<<foohtmlheader
    <header class="mainHeader">
        <div class="titlecontainer">
            <h1>$coauditsystem</h1>
            <img class="logo" alt="CAcert Logo" src="/res/img/CAcert-logo-colour-210tr.png"/>
        </div>
foohtmlheader;

    return $tabstrings;
}

/**
 * footer()
 * returns the footer for a page
 * @return
 */
function footer($secure = 0) {
    $url =  create_url('dataprotection', $secure);
    $url1 =  create_url('imprint', $secure);
    $tabstrings = <<<foohtmlfooter
    <div class="footerbar">
        <a href="$url">Data Protection</a>
        <a href="$url1">Imprint</a>
    </div>
foohtmlfooter;

    return $tabstrings;
}

/**
 * menu()
 * returns the menue for a page
 * @return
 */
function menu($secure = 0) {
    $db = new db_function();

    $back = _('back');
    $statistics = _('Statistics');
    $overview = _('Overview');
    $percountry = _('Per country');
    $coauditors = _('RA-Auditors');
    $newEntry = _('Enter new entry');
    $ownEntry = _('List own entries');
    $admin = _('Administrative view');
    $adminuser = _('List user');
    $adminsession = _('List sessions');
    $admintopic = _('List topics');
    $adminresult = _('List results');
    $adminview = _('List views');
    $adminkpi = _('List KPIs');
    $login = _('Login');
    $logout = _('Logout');
    $username = array_key_exists('name', $_SESSION ['user']) ? '[ ' . $_SESSION ['user'] ['name'] . ' ]' : '';
    $cid = array_key_exists('id', $_SESSION ['user']) ? '[ ' . $_SESSION ['user'] ['id'] . ' ]' : 0;
    //get session for the per country sessionn
    if (array_key_exists('csid',$_SESSION['user'])) {
        $csid = $_SESSION['user']['csid'];
    } else {
        $cs_res= $db -> get_all_session('WHERE `default` = 1');
        $csid = $cs_res['session_id'];
    }
    // $Admin

    $backurl = "#";
    $backclass = 'back_disabled';
    if (isset($_SERVER['HTTP_REFERER'])) {
        if ( strpos($_SERVER['HTTP_REFERER'], 'list') >0 ) {
            $backurl = $_SERVER['HTTP_REFERER'];
            $backclass = 'back_enabled';
        }
    }

    $url =  create_url('statistic', $secure);
    $url1 =  create_url('statistic', $secure, array('csid' => $csid));
    $tabstrings1 = <<<foohtmlnav1
        <nav>
            <!--div class="menubar"-->
            <ul >
                <li id="back" class="$backclass">
                    <a href="$backurl" class="$backclass">$back</a>
                </li>
                <li>
                    <a href="#">$statistics</a>
                    <ul>
                        <li>
                            <a href="$url">$overview</a>
                        </li>
                        <li>
                            <a href="$url1">$percountry</a>
                        </li>
                    </ul>
                </li>
foohtmlnav1;

    $url =  create_url('result', 1);
    $url1 =  create_url('resultlist', 1, array('cid' => $cid));
    $tabstrings2 = <<<foohtmlnav2
                <li><a href="#">$coauditors</a>
                    <ul>
                        <li>
                            <a href="$url">$newEntry</a>
                        </li>
                        <li>
                            <a href="$url1">$ownEntry</a>
                        </li>
                    </ul>
                </li>
foohtmlnav2;

    $url =  create_url('userlist', 1);
    $url1 =  create_url('sessionlist', 1);
    $url2 =  create_url('topiclist', 1);
    $url3 =  create_url('resultlist', 1);
    $url4 =  create_url('viewlist', 1);
    $url5 =  create_url('kpilist', 1);
    $tabstrings3 = <<<foohtmlnav3
                <li>
                    <a href="#"><span>$admin</span></a>
                    <ul class="sub-menu">
                        <li><a href="$url">$adminuser</a></li>
                        <li><a href="$url1">$adminsession</a></li>
                        <li><a href="$url2">$admintopic</a></li>
                        <li><a href="$url3">$adminresult</a></li>
                        <li><a href="$url4">$adminview</a></li>
                        <li><a href="$url5">$adminkpi</a></li>
                    </ul>
                </li>
foohtmlnav3;

    $url =  create_url('login', 1);
    $tabstrings4 = <<<foohtmlnav4
                <li>
                    <a href="$url">$login</a>
                </li>
foohtmlnav4;

    $url =  create_url('logout', 0);
    $tabstrings5 = <<<foohtmlnav5
                <li>
                    <a href="$url">$logout $username</a>
                </li>
foohtmlnav5;
    $tabstrings6 = <<<foohtmlnav6
            </ul>
            <!--/div-->
        </nav>
    </header>
foohtmlnav6;

    if ($_SESSION ['user'] ['read_permission'] == 1) {
        $tabstrings2 = '';
        $tabstrings3 = '';
        $tabstrings5 = '';
    } else {
        if (get_read_permission('adminmenue') < 1) {
            $tabstrings3 = '';
        }
        $tabstrings4 = '';
    }

    return $tabstrings1 . $tabstrings2 . $tabstrings3 . $tabstrings4 . $tabstrings5 . $tabstrings6;
}

/**
 * headerstart()
 * returns the header lines for a page
 * @param mixed $title
 * @return
 */
function headerstart($title) {
    $title = _('CAcert RA-Audit') . ' - ' . $title;

    $tabstrings = <<<foohtmlhead1
<!DOCTYPE html>
<html lang="en">
<head>
    <title>$title</title>
    <link rel="stylesheet" href="/res/css/template.css" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body class="body">
foohtmlhead1;

    return $tabstrings;
}

/**
 * footerend()
 * returns the footer tags for a page
 * @return
 */
function footerend() {
    $tabstrings = <<<foohtmlfoot1
</body>
</html>
foohtmlfoot1;

    return $tabstrings;
}
