<?php

$mysqli = new mysqli('localhost', 'coaudit', 'CAcert', 'coauditdb');
function test(){
    $mysqli = new mysqli('localhost', 'coaudit', 'CAcert', 'coauditdb');
    if (mysqli_connect_error()) {
        die('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
    }

    echo 'Success... ' . $mysqli->host_info . "\n";
}
function dbstart(){
    mysql_connect('localhost', 'coaudit', 'CAcert');
    mysql_select_db( 'coauditdb');
}

// user handling
function get_userid_from_mail($email){

    $query = "select `coauditor_id` from `coauditor` where  ` `email``='$email'";
    $res = mysql_query($query);
    if(mysql_num_rows($res) > 0)
    {
        return $res[0]['coauditor_id'];
    }
    return 0;
}


function get_userdata($uid){
    $uid = intval($uid);
    $result = array();
    $query = "select `coauditor_id`, `coauditor_name`, `email`, `read_permission`, `write_permission` from `coauditor` where  `coauditor_id`='$uid'";
    $res = mysql_query($query);
    if(mysql_num_rows($res) <= 0)
    {
        return $result;
    }
    $result['coauditid'] = $res[0]['coauditor_id'];
    $result['coauditor_name'] = $res[0]['coauditor_name'];
    $result['email'] = $res[0]['email'];
    $result['read_permission'] = $res[0]['read_permission'];
    $result['write_permission'] = $res[0]['write_permission'];
    return $result;
}

function get_user_write_permission($uid){
    $uid = intval($uid);
    $result = array();
    $query = "select  `read_permission`, `write_permission` from `coauditor` where  `coauditor_id`='$uid'";
    $res = mysql_query($query);
    if(mysql_num_rows($res) <= 0)
    {
        return 0;
    }
    return $res[0]['write_permission'];
}


function get_user_read_permission($uid){
    $uid = intval($uid);
    $result = array();
    $query = "select`read_permission` from `coauditor` where  `coauditor_id`='$uid'";
    $res = mysql_query($query);
    if(mysql_num_rows($res) <= 0)
    {
        return 0;
    }
    return $res[0]['write_permission'];
}

function insert_user($username, $email, $readpermission, $writepermission, $uid){

    $query = "Insert into `coauditor` (`coauditor_name`, `email`,
        `read_permission`, `write_permission`,
        `created_by`, `last_change`, `last_change_by`)
        VALUES ('$username', '$email',
        '$readpermission', '$writepermission',
        $uid, Now(), $uid)";
    mysql_query($query);
    $nid =mysql_insert_id();
    //write log

}

function update_user($username, $email, $readpermission, $writepermission, $uid, $cid){

    $query = "Update `coauditor` Set `coauditor_name` = ,
        `email` = '$email',
        `read_permission` = '$readpermission',
        `write_permission` = '$writepermission',
        `last_change` = Now(),
        `last_change_by` = $uid
        WHERE  `coauditor_id` = $cid";
    //write log

}

function update_userrights($readpermission, $writepermission, $uid, $cid){

    $query = "Update `coauditor` Set `read_permission` = '$readpermission',
        `write_permission` = '$writepermission',
        `last_change` = Now(),
        `last_change_by` = $uid
        WHERE  `coauditor_id` = $cid";
    //write log

}

function get_all_user(){
    $query = "select `coauditor_id`, `coauditor_name`, `email`, `read_permission`, `write_permission` from `coauditor` ORDER BY `coauditor_name`";
    $res = mysql_query($query);
    if (!$res) {
        die('Ungültige Abfrage: ' . mysql_error());
    }

    echo mysql_num_rows($res).'<br/>';
    return $res;
}

?>