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
    } else {
        while($row = mysql_fetch_assoc($res)){
            $result['coauditid'] = $row['coauditor_id'];
            $result['coauditor_name'] = $row['coauditor_name'];
            $result['email'] = $row['email'];
            $result['read_permission'] = $row['read_permission'];
            $result['write_permission'] = $row['write_permission'];
        }
    }
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

    $query = "Update `coauditor` Set `coauditor_name` = '$username',
        `email` = '$email',
        `read_permission` = '$readpermission',
        `write_permission` = '$writepermission',
        `last_change` = Now(),
        `last_change_by` = $uid
        WHERE  `coauditor_id` = $cid";
    mysql_query($query);
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
    return $res;
}

//topics handling
function get_all_topics($where = ''){
    if ($where == '') {
        $where = ' Where 1=1 ';
    }else{
        $where = ' Where session_topic_id='.$where.' ';
    }
    $query = "select `session_topic_id`, `session_topic`, `topic_explaination`, `activ` from `session_topic` " . $where . "ORDER BY `session_topic`";
    $res = mysql_query($query);
    if ($where == ' Where 1=1 ') {
            return $res;
    } else {
        $result = array();
        while($row = mysql_fetch_assoc($res)){
            $result['session_topic_id'] = $row['session_topic_id'];
            $result['session_topic'] = $row['session_topic'];
            $result['topic_explaination'] = $row['topic_explaination'];
            $result['activ'] = $row['activ'];
        }
        return $result;
    }
}


function insert_topic($topic, $explain){
    $query = "Insert into `session_topic` (`session_topic`, `topic_explaination`, `activ`)
        VALUES ('$topic', '$explain', 1)";
    mysql_query($query);
    $nid =mysql_insert_id();
    //write log

}

function update_topic($topic, $explain, $active, $tid){

    $query = "Update `session_topic` Set `session_topic` = '$topic',
        `topic_explaination` = '$explain',
        `activ` = '$active'
        WHERE  `session_topic_id` = $tid";
    mysql_query($query);
    //write log

}


?>