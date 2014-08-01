<?php

class db_function{

    public $db;

    function __construct(){
        include('dbconfig.php');
        $this -> db = New PDO("mysql:host=$dbhost;dbname=$dbdatabase", "$dbuser", "$dbpw");
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


    public function get_userdata($uid){
        $uid = intval($uid);
        $result = array();
        $query = "select `coauditor_id`, `coauditor_name`, `email`, `read_permission`, `write_permission` from `coauditor` where  `coauditor_id`='$uid'";
        $res = $this -> db -> query($query);
        if($res){
            return $res->fetch();
        } else {
            return $result;
        }
    }

    /**
     * db_function::get_user_write_permission()
     * returns the write permissions for a coauditor
     * @param mixed $uid
     * @return
     */
    public function get_user_write_permission($uid){
        $uid = intval($uid);
        $query = "select  `read_permission`, `write_permission` from `coauditor` where  `coauditor_id`='$uid'";
        if($res = $this->db->query(query)){
            return $res[0]['write_permission'];
        } else{
            return 0;
        }
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

    /**
     * db_function::insert_user()
     * inserts new user data
     * @param mixed $username
     * @param mixed $email
     * @param mixed $readpermission
     * @param mixed $writepermission
     * @param mixed $uid    id of user adding the data
     * @return
     */
    public function insert_user($username, $email, $readpermission, $writepermission, $uid){

        $query = "Insert into `coauditor` (`coauditor_name`, `email`,
            `read_permission`, `write_permission`,
            `created_by`, `last_change`, `last_change_by`)
            VALUES ('$username', '$email',
            '$readpermission', '$writepermission',
            $uid, Now(), $uid)";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        //$nid = $smt -> lastInsertedId();
        //write log

    }

    /**
     * db_function::update_user()
     *updates the data of a given user
     * @param mixed $username
     * @param mixed $email
     * @param mixed $readpermission
     * @param mixed $writepermission
     * @param mixed $uid    id of user adding the data
     * @param mixed $cid    id of dataset changed
     * @return
     */
    public function update_user($username, $email, $readpermission, $writepermission, $uid, $cid){

        $query = "Update `coauditor` Set `coauditor_name` = '$username',
            `email` = '$email',
            `read_permission` = '$readpermission',
            `write_permission` = '$writepermission',
            `last_change` = Now(),
            `last_change_by` = $uid
            WHERE  `coauditor_id` = $cid";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
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

    /**
     * db_function::get_all_user()
     * returns all coauditors entries
     * @return
     */
    public function get_all_user(){
        $query = "select `coauditor_id`, `coauditor_name`, `email`, `read_permission`, `write_permission` from `coauditor` ORDER BY `coauditor_name`";
        $res = $this -> db -> query($query);
        if($res){
            return $res->fetchAll();
        }
    }

//topics handling
    /**
     * db_function::get_all_topics()
     * returns all recorded topics, if where is not given all topics, if given only the requested topic
     * @param string $where
     * @return
     */
    public function get_all_topics($where = ''){
        if ($where == '') {
            $where = ' Where 1=1 ';
        }else{
            $where = ' Where session_topic_id='.$where.' ';
        }
        $query = "select `session_topic_id`, `session_topic`, `topic_explaination`, `activ` from `session_topic` " . $where . "ORDER BY `session_topic`";
        $res = $this -> db -> query($query);
        if($where == ' Where 1=1 '){
            return $res->fetchAll();
        } else {
            return $res->fetch();
        }
    }


    /**
     * db_function::insert_topic()
     * inserts a new topic
     * @param mixed $topic
     * @param mixed $explain
     * @return
     */
    public function insert_topic($topic, $explain){
        $query = "Insert into `session_topic` (`session_topic`, `topic_explaination`, `activ`)
            VALUES ('$topic', '$explain', 1)";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        //$nid = $smt -> lastInsertedId();
        //write log

    }

    /**
     * db_function::update_topic()
     *  updates the data of a topic
     * @param mixed $topic
     * @param mixed $explain
     * @param mixed $active
     * @param mixed $tid
     * @return
     */
    public function update_topic($topic, $explain, $active, $tid){

        $query = "Update `session_topic` Set `session_topic` = '$topic',
            `topic_explaination` = '$explain',
            `activ` = '$active'
            WHERE  `session_topic_id` = $tid";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        //write log
    }

// session handling
function get_all_session($where = ''){
    if ($where == '') {
        $where = ' Where 1=1 ';
    }else{
        $where = ' Where session_id='.$where.' ';
    }
    $query = "select `session_id`, `session_name`, `from`, `to`, `active` from `coauditsession` " . $where . "ORDER BY `session_name`";
    $res = mysql_query($query);
    if ($where == ' Where 1=1 ') {
        return $res;
    } else {
        $result = array();
        while($row = mysql_fetch_assoc($res)){
            $result['session_id'] = $row['session_id'];
            $result['session_name'] = $row['session_name'];
            $result['from'] = $row['from'];
            $result['to'] = $row['to'];
            $result['active'] = $row['active'];
        }
        return $result;
    }
}

function insert_session($session_name, $from, $to){
    $query = "Insert into `coauditsession` (`session_name`, `from`, `to`, `active`)
        VALUES ('$session_name', '$from', '$to', 1)";
    mysql_query($query);
    $nid =mysql_insert_id();
    //write log

}

function update_session($session_name, $from, $to, $active, $sid){

    $query = "Update `coauditsession` Set `session_name` = '$session_name',
        `from` = '$from',
        `to` = '$to',
        `active` = '$active'
        WHERE  `session_id` = $sid";
    mysql_query($query);
    //write log

}

// session topic handling
function get_all_sessiontopics($where = ''){

    if ($where == '') {
        $where = '';
    }else{
        $where = ' and ' . $where;
    }

    $query = "SELECT `st`.`session_topics_id`, `st`.`session_topic_id` , `t`.`session_topic` , `s`.`session_name` , `st`.`topic_no` , `st`.`active`
                FROM `session_topics` AS `st` , `coauditsession` AS `s` , `session_topic` AS `t`
                WHERE `st`.`session_topic_id` = `t`.`session_topic_id`
                AND `st`.`coaudit_session_id` = `s`.`session_id` " . $where ."
                ORDER BY `s`.`session_name` , `st`.`topic_no`";
    $res = mysql_query($query);
/*
    if ($where == '') {
        return $res;
    } else {
        $result = array();
        while($row = mysql_fetch_assoc($res)){
            $result['session_topics_id'] = $row['session_topics_id'];
            $result['session_topic'] = $row['session_topic'];
            $result['session_name'] = $row['session_name'];
            $result['topic_no'] = $row['topic_no'];
            $result['active'] = $row['active'];
        }
        return $result;
    }
*/
    return $res;
}

function get_sessiontopic($stid){
/*
   if ($where == '') {
   $where = ' Where 1=1 ';
   }else{
   $where = ' Where session_topics_id='.$where.' ';
   }
*/
    $query = "SELECT `session_topics_id` , `session_topic_id` , `coaudit_session_id` ,
            `topic_no` , `active`
            FROM `session_topics`
            WHERE `session_topics_id` = " . $stid;
    $res = mysql_query($query);

    $result = array();
    while($row = mysql_fetch_assoc($res)){
        $result['session_topics_id'] = $row['session_topics_id'];
        $result['session_topic_id'] = $row['session_topic_id'];
        $result['coaudit_session_id'] = $row['coaudit_session_id'];
        $result['topic_no'] = $row['topic_no'];
        $result['active'] = $row['active'];
    }

    return $result;
}


function insert_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no){
    $query = "Insert into `session_topics` (`session_topic_id`, `coaudit_session_id`, `topic_no`, `active`)
        VALUES ('$session_topic_id', '$coaudit_session_id', '$topic_no', 1)";
    mysql_query($query);
    $nid =mysql_insert_id();
    //write log

}

function update_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no, $active, $stid){

    $query = "Update `session_topics` Set `session_topic_id` = '$session_topic_id',
        `coaudit_session_id` = '$coaudit_session_id',
        `topic_no` = '$topic_no',
        `active` = '$active'
        WHERE  `session_topics_id` = $stid";
    mysql_query($query);
    //write log

}

    // view handling
    /**
     * db_function::get_all_view()
     * returns all recorded views, if where is not given all views, if given only the requested view
     * @param string $where name of the requested view
     * @return
     */
    public function get_all_view($where = ''){
        if ($where == '') {
            $where = ' Where 1=1 ';
        }else{
            $where = ' Where view_rigths_id='.$where.' ';
        }
        $query = "select `view_rigths_id`, `view_name`, `read_permission`, `write_permission`, `active` from `view_rights` " . $where . "ORDER BY `view_name`";
        $res = $this -> db -> query($query);
        if($where == ' Where 1=1 '){
            return $res->fetchAll();
        } else {
            return $res->fetch();
        }
    }

    /**
     * db_function::insert_view()
     * inserts a new view
     * @param mixed $view_name
     * @param mixed $read_permission
     * @param mixed $write_permission
     * @return
     */
    public function insert_view($view_name, $read_permission, $write_permission){
        $query = "Insert into `view_rights` (`view_name`, `read_permission`, `write_permission`, `active`)
            VALUES ('$view_name', '$read_permission', '$write_permission', 1)";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        //$nid = $smt -> lastInsertedId();
        //write log

    }

    /**
     * db_function::update_view()
     * updates the data of a view
     * @param mixed $view_name
     * @param mixed $read_permission
     * @param mixed $write_permission
     * @param mixed $active
     * @param mixed $vid
     * @return
     */
    public function update_view($view_name, $read_permission, $write_permission, $active, $vid){

        $query = "Update `view_rights` Set `view_name` = '$view_name',
            `read_permission` = '$read_permission',
            `write_permission` = '$write_permission',
            `active` = '$active'
            WHERE `view_rigths_id` = $vid";
        $smt = $this -> db -> prepare($query);
        $smt -> execute();
        //write log

    }

    /**
     * db_function::get_view_right()
     *  retruns the permmisons for a given view
     * @param mixed $view name of the view
     * @return
     */
    public function get_view_right($view){
        $query = "SELECT `view_name` , `read_permission` , `write_permission`, `active`
                    FROM `view_rights`
                    WHERE `view_name` = '$view'";
        $res = $this -> db -> query($query);

        $result = $res->fetch();
/*
        }

        $res = mysql_query($query);
        $result = array();
        while($row = mysql_fetch_assoc($res)){
            $result['view_name'] = $row['view_name'];
            $result['read_permission'] = $row['read_permission'];
            $result['write_permission'] = $row['write_permission'];
            $result['active'] = $row['active'];
        }
*/
        return $result;
    }



// result management

function insert_result_user($primaryemail, $assurer, $expierencepoints, $country, $location, $coauditdate){
    $query = "Insert into `cacertuser` (`primaryemail`, `webdb_account_id`, `assurer`, `expierencepoints`,
        `country`, `created_by`, `location`, `coauditdate`, `active`)
        VALUES ('$primaryemail', 0, '$assurer', $expierencepoints,
        '$country', " . $_SESSION['user']['id'] . ", '$location', '$coauditdate', 1)";
    mysql_query($query);
    $nid =mysql_insert_id();
    //write log
    return $nid;
}

function update_result_user($view_name, $read_permission, $write_permission, $active, $vid){

    $query = "Update `view_rights` Set `view_name` = '$view_name',
        `read_permission` = '$read_permission',
        `write_permission` = '$write_permission',
        `active` = '$active'
        WHERE `view_rigths_id` = $vid";
    mysql_query($query);
    //write log

}

function get_results($session = 0, $coauditid = 0){
    $where = '';
    if ($session != 0) {
        $where .= ' and `co`.`session_id` = ' . intval($session);
    }
    if ($coauditid != 0) {
        $where .= ' and `aud`.`coauditor_id` = ' . intval($coauditid);
    }
    $query = "SELECT `co`.`session_name` AS `Session` , year( `c`.`coauditdate` ) AS `CYear` ,
                `sts`.`topic_no` AS `Topic_No` , `st`.`session_topic` AS `Topic` ,
                `r`.`result` AS `Result`, `st`.`session_topic_id` AS `TopicID` ,
                `r`.`coauditsession_id` AS `SessionID`,
                `c`.`primaryemail` as `Assurer` , `aud`.`coauditor_name` as `Coauditor`,
                `r`.`comment` AS `Comment`
                FROM `cacertuser` AS `c` , `result` AS `r` , `session_topic` AS `st` , `coauditsession` AS `co` , `session_topics` AS `sts`, `coauditor` AS `aud`
                WHERE `c`.`cacertuser_id` = `r`.`cacertuser_id` AND `r`.`session_topic_id` = `st`.`session_topic_id`
                    AND `r`.`coauditsession_id` = `co`.`session_id`
                    AND (`sts`.`session_topic_id` = `r`.`session_topic_id` AND `sts`.`coaudit_session_id` = `r`.`coauditsession_id`)
                    AND `r`.`coauditor_id` = `aud`.`coauditor_id`
                    AND `c`.`deleted` IS NULL
                    $where
                ORDER BY `CYear` , `Session` , `Assurer`, `Coauditor`, `Topic_No`";
    $res = mysql_query($query);
    return $res;
}


function insert_result_topic($session_topic_id, $coauditsession_id, $cacertuser_id, $result, $comment){
    $query = "Insert into `result` (`session_topic_id`, `coauditsession_id`, `cacertuser_id`, `coauditor_id`,
        `result`, `comment`, `active`)
        VALUES ($session_topic_id, $coauditsession_id, $cacertuser_id, " .$_SESSION['user']['id'] . ",
        '$result', '$comment', 1)";
    mysql_query($query);
    $nid =mysql_insert_id();
    //write log

}

function update_result_topic($view_name, $read_permission, $write_permission, $active, $vid){
    $query = "Update `view_rights` Set `view_name` = '$view_name',
        `read_permission` = '$read_permission',
        `write_permission` = '$write_permission',
        `active` = '$active'
        WHERE `view_rigths_id` = $vid";
    mysql_query($query);
    //write log

}



//statistics

function get_statiscs_basic($where =''){
    $query = "SELECT `co`.`session_name` AS `Session` , year( `c`.`coauditdate` ) AS `CYear` , `sts`.`topic_no` AS `Topic_No` ,
        `st`.`session_topic` AS `Topic` , sum( `r`.`result` ) AS `res` , count( `r`.`result` ) AS `Total` ,  (sum(`r`.`result`) /  count(`r`.`result`))*100 as `Perc`,
        `st`.`session_topic_id` AS `TopicID` , `r`.`coauditsession_id` AS `SessionID`
        FROM `cacertuser` AS `c` , `result` AS `r` , `session_topic` AS `st` , `coauditsession` AS `co` , `session_topics` AS `sts`
        WHERE `c`.`cacertuser_id` = `r`.`cacertuser_id` AND `r`.`session_topic_id` = `st`.`session_topic_id`
            AND `r`.`coauditsession_id` = `co`.`session_id`
            AND (`sts`.`session_topic_id` = `r`.`session_topic_id` AND `sts`.`coaudit_session_id` = `r`.`coauditsession_id`)
            AND `c`.`deleted` is Null " . $where ."
        GROUP BY `CYear` , `Topic` , `Session` , `TopicID` , `Topic_No` , `SessionID`
        ORDER BY `CYear` , `Session` , `Topic_No`";
    $res = mysql_query($query);
    return $res;

}

function get_statiscs_kpi($where =''){
    $query = "Select `session_year`, `assurances`, `target` from `coaudit_refdata` where `coaudit_session_id` = " . $where;
    $res = mysql_query($query);
    return $res;

}

}
?>