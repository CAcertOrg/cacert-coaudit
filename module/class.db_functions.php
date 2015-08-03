<?php

class db_function {

    public $db;

    var $salt = '';

    /**
     * db_function::__construct()
     * constructor of the class
     */
    public function __construct() {
        include 'dbconfig.php';
        $this->db = new PDO("mysql:host=$dbhost;dbname=$dbdatabase", "$dbuser", "$dbpw");
        $salt = $dbsalt;
    }

    /**
     * db_function::tidystring()
     *  tidy input string
     * @param mixed $input
     * @return
     */
    public function tidystring($input) {
       return $this->db->quote($input);
    }

    // user handling
    /**
     * db_function::get_userid_from_mail()
     * returns the id of a coauditor entry if the email matches
     * @param mixed $email
     * @return
     */
    public function get_userid_from_mail($email) {
        $query = "select `coauditor_id` from `coauditor` where `email`='$email'";
        $res = $this->db->query($query);
        if($res){
            $result = $res->fetch();
            return $result['coauditor_id'];
        } else {
            return 0;
        }
    }

    /**
     * db_function::get_userdata()
     * returns the data of a coauditor
     * @param mixed $uid
     * @return
     */
    public function get_userdata($uid){
        $uid = intval($uid);
        $result = array();

        $query = "select `coauditor_id`, `coauditor_name`, `email`, `read_permission`, `write_permission` from `coauditor` where  `coauditor_id`='$uid'";
        $res = $this->db->query($query);
        if($res) {
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
    public function get_user_write_permission($uid) {
        $uid = intval($uid);

        $query = "select  `read_permission`, `write_permission` from `coauditor` where  `coauditor_id`='$uid'";
        if($res = $this->db->query(query)){
            return $res[0]['write_permission'];
        } else{
            return 0;
        }
    }

    /**
     * db_function::get_user_read_permission()
     * returns the read permissions for a coauditor
     * @param mixed $uid
     * @return
     */
    public function get_user_read_permission($uid) {
        $uid = intval($uid);
        $result = array();

        $query = "select`read_permission` from `coauditor` where  `coauditor_id`='$uid'";
        if($res = $this->db->query(query)) {
            return $res[0]['read_permission'];
        } else {
            return 0;
        }
    }

    /**
     * db_function::get_view_permissions()
     * returns the permissons for the different views
     * @return
     */
    function get_view_permissions(){
        $query = "SELECT `view_name`, `read_permission`, `write_permission` FROM  `view_rights` WHERE `active` = 1";
        $res = $this->db->query($query);
        return $res->fetchAll();
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
    public function insert_user($username, $email, $readpermission, $writepermission, $uid) {
        $query = "Insert into `coauditor` (`coauditor_name`, `email`,
            `read_permission`, `write_permission`,
            `created_by`, `last_change`, `last_change_by`)
            VALUES ($username, $email,
            '$readpermission', '$writepermission',
            $uid, Now(), $uid)";

        $smt = $this->db->prepare($query);
        $smt->execute();
        $nid = $this->db->lastInsertId();

        //write log
        write_log('admin', $nid, "added account for $email");
    }

    /**
     * db_function::update_user()
     * updates the data of a given user
     * @param mixed $username
     * @param mixed $email
     * @param mixed $readpermission
     * @param mixed $writepermission
     * @param mixed $uid    id of user adding the data
     * @param mixed $cid    id of dataset changed
     * @return
     */
    public function update_user($username, $email, $readpermission, $writepermission, $uid, $cid) {
        $query = "Update `coauditor` Set `coauditor_name` = $username,
            `email` = $email,
            `read_permission` = '$readpermission',
            `write_permission` = '$writepermission',
            `last_change` = Now(),
            `last_change_by` = $uid
            WHERE  `coauditor_id` = $cid";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('admin', $cid, "update account of $email");
    }


    /**
     * db_function::get_all_user()
     * returns all coauditors entries
     * @return
     */
    public function get_all_user() {
        $query = "select `coauditor_id`, `coauditor_name`, `email`, `read_permission`, `write_permission` from `coauditor` ORDER BY `coauditor_name`";
        $res = $this -> db -> query($query);
        if($res) {
            return $res->fetchAll();
        } else {
            return false;
        }
    }

    //topics handling

    /**
     * db_function::get_all_topics()
     * returns all recorded topics, if where is not given all topics, if given only the requested topic
     * @param string $where
     * @return
     */
    public function get_all_topics($where = '') {
        if ($where == '') {
            $where = ' Where 1=1 ';
        } else {
            $where = ' Where session_topic_id=' . $where . ' ';
        }

        $query = "select `session_topic_id`, `session_topic`, `topic_explaination`, `activ` from `session_topic` " . $where . "ORDER BY `session_topic`";
        $res = $this->db->query($query);
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
    public function insert_topic($topic, $explain) {
        $query = "Insert into `session_topic` (`session_topic`, `topic_explaination`, `activ`)
            VALUES ($topic, $explain, 1)";

        $smt = $this->db->prepare($query);
        $smt->execute();
        $nid = $this->db->lastInsertId();

        //write log
        write_log('admin', $nid, "added topic $topic");
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
    public function update_topic($topic, $explain, $active, $tid) {
        $query = "Update `session_topic` Set `session_topic` = $topic,
            `topic_explaination` = $explain,
            `activ` = $active
            WHERE  `session_topic_id` = $tid";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('admin', $tid, "update topic $topic");
    }

    // session handling

    /**
     * db_function::get_all_session()
     * returns all recorded sessions, if where is not given all sessions, if given only the requested session
     * @param string $where
     * @return
     */
    function get_all_session($where = '') {
        if ($where == '') {
            $where = ' Where 1=1 ';
        } else {
            if (is_numeric($where)) {
                $where = ' Where session_id=' . $where . ' ';
            } else {
                $where = ' ' . $where . ' ';
            }
        }

        $query = "select `session_id`, `session_name`, `from`, `to`, `default`, `active` from `coauditsession` " . $where . " ORDER BY `session_name`";
        $res = $this -> db -> query($query);
        if($where == ' Where 1=1 '){
            return $res->fetchAll();
        } else {
            return $res->fetch();
        }
    }

    /**
     * db_function::insert_session()
     * adds a new session
     * @param mixed $session_name   session name
     * @param mixed $from           date showing the start date for the session
     * @param mixed $to             date showing the end date for the session
     * @return
     */
    public function insert_session($session_name, $from, $to, $default) {
        $query = "Insert into `coauditsession` (`session_name`, `from`, `to`, `default`, `active`)
            VALUES ($session_name, $from, $to, 1)";

        $smt = $this->db->prepare($query);
        $smt->execute();
        $nid = $this->db->lastInsertId();

        //write log
        write_log('admin', $nid, "added session $session_name");
    }

    /**
     * db_function::update_session()
     *  update the session information
     * @param mixed $session_name   session name
     * @param mixed $from           date showing the start date for the session
     * @param mixed $to             date showing the end date for the session
     * @param mixed $active         shows if the session is visible for choices
     * @param mixed $sid            id of the session
     * @return
     */
    public function update_session($session_name, $from, $to, $default, $active, $sid) {
        $query = "Update `coauditsession` Set `session_name` = $session_name,
            `from` = $from,
            `to` = $to,
            `default` = $default,
            `active` = $active
            WHERE  `session_id` = $sid";
        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('admin', $sid, "updated session $session_name");
    }

    // session topic handling
    /**
     * db_function::get_all_sessiontopics()
     * returns all recorded topics linked to sessions, if where is not given all data, if given only the requested values
     * @param string $where
     * @return
     */
    public function get_all_sessiontopics($where = '') {
        if ($where == '') {
            $where = '';
        } else {
            $where = ' and ' . $where;
        }

        $query = "SELECT `st`.`session_topics_id`, `st`.`session_topic_id` , `t`.`session_topic` , `s`.`session_name` , `st`.`topic_no` , `st`.`active`
                    FROM `session_topics` AS `st` , `coauditsession` AS `s` , `session_topic` AS `t`
                    WHERE `st`.`session_topic_id` = `t`.`session_topic_id`
                    AND `st`.`coaudit_session_id` = `s`.`session_id` " . $where ."
                    ORDER BY `s`.`session_name` , `st`.`topic_no`";
        $res = $this->db->query($query);
        return $res->fetchAll();
    }

    /**
     * db_function::get_result_topics()
     * returns all listed result topics
     * @param mixed $sid
     * @param mixed $uid
     * @return
     */
    public function get_result_topics($sid, $uid) {
        $query = "SELECT `st`.`session_topics_id`, `st`.`session_topic_id` , `t`.`session_topic` , `s`.`session_name` ,
                    `st`.`topic_no` , `st`.`active`,  `r`.`result`, `r`.`comment` ,  `r`.`cacertuser_id`
                    FROM `session_topics` AS `st` , `coauditsession` AS `s` , `session_topic` AS `t`, `result` AS `r`
                    WHERE `st`.`session_topic_id` = `t`.`session_topic_id`
                        AND `st`.`coaudit_session_id` = `s`.`session_id`
                        AND `st`.`session_topic_id` = `r`.`session_topic_id`
                        AND `r`.`cacertuser_id` = " . $uid . "
                        AND `s`.`session_id` = " . $sid . "
                    ORDER BY `s`.`session_name` , `st`.`topic_no`";

        $res = $this->db->query($query);

        return $res;
    }

    /**
     * db_function::get_sessiontopic()
     *returns the data for a given session topic
     * @param mixed $stid
     * @return
     */
    public function get_sessiontopic($stid) {
        $query = "SELECT `session_topics_id` , `session_topic_id` , `coaudit_session_id` ,
                `topic_no` , `active`
                FROM `session_topics`
                WHERE `session_topics_id` = " . $stid;

        $res = $this->db->query($query);

        return $res->fetch();
    }


    /**
     * db_function::insert_sessiontopics()
     * adds a session topic
     * @param mixed $session_topic_id
     * @param mixed $coaudit_session_id
     * @param mixed $topic_no
     * @return
     */
    public function insert_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no) {
        $query = "Insert into `session_topics` (`session_topic_id`, `coaudit_session_id`, `topic_no`, `active`)
                VALUES ('$session_topic_id', '$coaudit_session_id', '$topic_no', 1)";

        $smt = $this->db->prepare($query);
        $smt->execute();
        $nid = $this->db->lastInsertId();

        //write log
        write_log('admin', $nid, "added sessiontopic");
    }

    /**
     * db_function::update_sessiontopics()
     * updates a session topic
     * @param mixed $session_topic_id
     * @param mixed $coaudit_session_id
     * @param mixed $topic_no
     * @param mixed $active
     * @param mixed $stid
     * @return
     */
    public function update_sessiontopics($session_topic_id, $coaudit_session_id, $topic_no, $active, $stid) {
        $query = "Update `session_topics` Set `session_topic_id` = '$session_topic_id',
            `coaudit_session_id` = '$coaudit_session_id',
            `topic_no` = '$topic_no',
            `active` = '$active'
            WHERE  `session_topics_id` = $stid";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('admin', $stid, "updated sessiontopic");
    }


    /**
     * db_function::check_no_sessiontopics()
     * returns the session topics if they are entered more than once as active topic
     * @param mixed $session_id
     * @return
     */
    public function check_no_sessiontopics($session_id) {
        $query = "SELECT `sts`.`session_topic_id`, COUNT(`sts`.`session_topic_id`) AS Numbers,  `st`.`session_topic`
            FROM  `session_topics` AS `sts`, `session_topic` AS `st`
            WHERE `sts`.`active` = 1 AND  `st`.`session_topic_id` = `sts`.`session_topic_id` AND `sts`.`coaudit_session_id` = $session_id
            GROUP BY `sts`.`session_topic_id`,  `st`.`session_topic`
            HAVING COUNT(`sts`.`session_topic_id`) > 1 ";

        $res = $this->db->query($query);

        return $res->fetchAll();
    }


    // view handling

    /**
     * db_function::get_all_view()
     * returns all recorded views, if where is not given all views, if given only the requested view
     * @param string $where name of the requested view
     * @return
     */
    public function get_all_view($where = '') {
        if ($where == '') {
            $where = ' Where 1=1 ';
        } else {
            $where = ' Where view_rigths_id='.$where.' ';
        }

        $query = "select `view_rigths_id`, `view_name`, `read_permission`, `write_permission`, `active` from `view_rights` " . $where . "ORDER BY `view_name`";
        $res = $this->db->query($query);

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
    public function insert_view($view_name, $read_permission, $write_permission) {
        $query = "Insert into `view_rights` (`view_name`, `read_permission`, `write_permission`, `active`)
            VALUES ($view_name, '$read_permission', '$write_permission', 1)";

        $smt = $this->db->prepare($query);
        $smt->execute();
        $nid = $this->db->lastInsertId();

        //write log
        write_log('admin', $nid, "added view $view_name");
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
    public function update_view($view_name, $read_permission, $write_permission, $active, $vid) {
        $query = "Update `view_rights` Set `view_name` = $view_name,
            `read_permission` = '$read_permission',
            `write_permission` = '$write_permission',
            `active` = '$active'
            WHERE `view_rigths_id` = $vid";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('admin', $vid, "updated view $view_name");
    }

    /**
     * db_function::get_view_right()
     *  retruns the permmisons for a given view
     * @param mixed $view name of the view
     * @return
     */
    public function get_view_right($view) {
        $query = "SELECT `view_name` , `read_permission` , `write_permission`, `active`
                    FROM `view_rights`
                    WHERE `view_name` = '$view'";
        $res = $this->db->query($query);

        $result = $res->fetch();
        return $result;
    }

    // result management

    /**
     * db_function::insert_result_user()
     *
     * @param mixed $primaryemail
     * @param mixed $assurer
     * @param mixed $expierencepoints
     * @param mixed $country
     * @param mixed $location
     * @param mixed $coauditdate
     * @return
     */
    public function insert_result_user($primaryemail, $assurer, $expierencepoints, $country, $location, $coauditdate) {
        global $salt;

        $primaryemail = hash_hmac('sha256', $primaryemail, $salt);

        $query = "Insert into `cacertuser` (`primaryemail`, `assurer`, `expierencepoints`,
            `country`, `created_by`, `location`, `coauditdate`, `active`)
            VALUES ('$primaryemail', '$assurer', $expierencepoints,
            '$country', " . $_SESSION['user']['id'] . ", '$location', '$coauditdate', 1)";

        $smt = $this->db->prepare($query);
        $smt->execute();
        $nid = $this->db->lastInsertId();

        //write log
        write_log('user', $nid, "added cacertuser '$nid'");

        return $nid;
    }

    /**
     * db_function::update_result_user()
     *
     * @param mixed $primaryemail
     * @param mixed $assurer
     * @param mixed $expierencepoints
     * @param mixed $country
     * @param mixed $location
     * @param mixed $coauditdate
     * @param mixed $userid
     * @return
     */
    public function update_result_user($primaryemail, $assurer, $expierencepoints, $country, $location, $coauditdate, $userid) {
        if (strpos( $primaryemail, '@') !== false) {
            global $salt;
            $primaryemail = hash_hmac('sha256', $primaryemail, $salt);
        }

        $query = "Update `cacertuser` Set `primaryemail` = '$primaryemail',
            `assurer` = '$assurer',
            `expierencepoints` = '$expierencepoints',
            `country` = '$country',
            `location` = '$location',
            `coauditdate` = '$coauditdate'
            WHERE `cacertuser_id` = $userid";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('user', $userid, "updated cacertuser '$userid'");
    }

    /**
     * db_function::get_result_user()
     *  returns the data for a cacert user
     * @param mixed $uid
     * @return
     */
    public function get_result_user($uid) {
        $query = "SELECT `primaryemail` , `assurer` , `expierencepoints`, `country`, `location`, `coauditdate`,  `created`
                    FROM `cacertuser`
                    WHERE `cacertuser_id` = '$uid'";

        $res = $this->db->query($query);
        $result = $res->fetch();

        return $result;
   }

    /**
     * db_function::get_results()
     * returns tke results
     * @param integer $session      if given filter on the session
     * @param integer $coauditid    if given filter on the RA-Auditor
     * @return
     */
    public function get_results($session = 0, $coauditid = 0, $sessionyear = 0) {
        $where = '';
        if ($session) {
            $where .= ' AND `co`.`session_id` = ' . intval($session);
        }
        if ($coauditid) {
            $where .= ' AND `aud`.`coauditor_id` = ' . intval($coauditid);
        }
        if ($sessionyear) {
            $where .= ' AND  YEAR( `c`.`coauditdate` ) = ' . intval($sessionyear);
        }
        $query = "SELECT `co`.`session_name` AS `Session` , YEAR( `c`.`coauditdate` ) AS `CYear` ,
                    `sts`.`topic_no` AS `Topic_No` , `st`.`session_topic` AS `Topic` ,
                    `r`.`result` AS `Result`, `st`.`session_topic_id` AS `TopicID` ,
                    `r`.`coauditsession_id` AS `SessionID`,
                    `c`.`primaryemail` AS `Assurer` , `c`.`cacertuser_id` AS `uid`, `aud`.`coauditor_name` AS `Coauditor`,
                    `r`.`comment` AS `Comment`
                    FROM `cacertuser` AS `c` , `result` AS `r` , `session_topic` AS `st` , `coauditsession` AS `co` , `session_topics` AS `sts`, `coauditor` AS `aud`
                    WHERE `c`.`cacertuser_id` = `r`.`cacertuser_id` AND `r`.`session_topic_id` = `st`.`session_topic_id`
                        AND `r`.`coauditsession_id` = `co`.`session_id`
                        AND (`sts`.`session_topic_id` = `r`.`session_topic_id` AND `sts`.`coaudit_session_id` = `r`.`coauditsession_id`)
                        AND `r`.`coauditor_id` = `aud`.`coauditor_id`
                        AND `c`.`deleted` IS NULL
                        $where
                    ORDER BY  `Session`, `CYear` , `Coauditor`, `uid`, `Topic_No`";

        $res = $this->db->query($query);

        return $res;
    }


    /**
     * db_function::insert_result_topic()
     *
     * @param mixed $session_topic_id
     * @param mixed $coauditsession_id
     * @param mixed $cacertuser_id
     * @param mixed $result
     * @param mixed $comment
     * @return
     */
    public function insert_result_topic($session_topic_id, $coauditsession_id, $cacertuser_id, $result, $comment) {
        if ($comment == '') {
            $comment = 'NULL';
        }

        $query = "Insert into `result` (`session_topic_id`, `coauditsession_id`, `cacertuser_id`, `coauditor_id`,
            `result`, `comment`, `active`)
            VALUES ($session_topic_id, $coauditsession_id, $cacertuser_id, " .$_SESSION['user']['id'] . ",
            '$result', $comment, 1)";

        $smt = $this->db->prepare($query);
        $smt->execute();
        $nid = $this->db->lastInsertId();

        //write log
        write_log('user', $nid, "added result");
    }

    /**
     * db_function::update_result_topic()
     *
     * @param mixed $session_topic_id
     * @param mixed $coauditsession_id
     * @param mixed $cacertuser_id
     * @param mixed $result
     * @param mixed $comment
     * @param mixed $rid
     * @return
     */
    public function update_result_topic($session_topic_id, $coauditsession_id, $cacertuser_id, $result, $comment) {
/*
       $query = "Update `result` Set `session_topic_id` = '$session_topic_id',
            `coauditsession_id` = '$coauditsession_id',
            `cacertuser_id` = '$cacertuser_id',
            `coauditor_id` = " .$_SESSION['user']['id'] . ",
            `result` = '$result',
            `comment` = '$comment',
            WHERE `result_id` = $rid";
*/
        $comment = str_replace("'", "", $comment);
        $session_topic_id = str_replace("'", "", $session_topic_id);
        $query = "Update `result` Set `result` = '$result',
            `comment` = '$comment'
            WHERE `session_topic_id` = '$session_topic_id' AND
            `cacertuser_id` = '$cacertuser_id'";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('user', $session_topic_id . ' ' . $cacertuser_id, "updated result");
    }

    /**
     * db_function::delete_result()
     * sets the delete flag to all result entries
     * @param mixed $uid
     * @param mixed $sid
     * @return
     */
    public function delete_result($uid, $sid) {
        $this->delete_cacertuser($uid);

        $query = "SELECT`result_id` FROM `result`
            where `coauditsession_id` = $sid and `cacertuser_id` = $uid";

        $res = $this->db->query($query);

        foreach($res as $result){
            $this->delete_resultentry($result[0]);
        }
    }

    /**
     * db_function::delete_cacertuser()
     * sets the delete flag to cacert user entry
     * @param mixed $uid
     * @return
     */
    public function delete_cacertuser($uid) {
        $query = "Update `cacertuser` Set `active` = 0,
            `deleted` = NOW(), `deleted_by` = " . $_SESSION['user']['id'] . "
            WHERE `cacertuser_id` = '$uid'";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('user', $uid, "deleted cacertuser");
    }

    /**
     * db_function::delete_resultentry()
     * sets the delete flag to all result entries
     * @param mixed $rid
     * @return
     */
    public function delete_resultentry($rid) {
        $query = "Update `result` Set `active` = 0,
            `deleted` = NOW(), `deleted_by` = " . $_SESSION['user']['id'] . "
            WHERE `result_id` = '$rid'";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('user', $rid, "deleted result entry");
    }

    // kpi handling

    /**
     * db_function::get_all_kpi()
     * returns all kpi
     * @param string $where
     * @return
     */
    function get_all_kpi($where = '') {
        if ($where == '') {
            $where = ' and 1=1 ';
        } else {
            $where = ' and '.$where.' ';
        }

        $query = "SELECT `c`.`coaudit_refdata_id` , `c`.`session_year` , `c`.`assurances` , `c`.`target` , `s`.`session_id`, `s`.`session_name`
            FROM `coaudit_refdata` AS `c` , `coauditsession` AS `s`
            WHERE `c`.`coaudit_session_id` = `s`.`session_id` $where
            ORDER BY `s`.`session_id`,`c`.`session_year`";

        $res = $this->db->query($query);

        if($where == ' and 1=1 '){
            return $res->fetchAll();
        } else {
            return $res->fetch();
        }
    }

    /**
     * db_function::insert_kpi()
     * adds kpi information
     * @param mixed $session_id
     * @param mixed $session_year
     * @param mixed $assurances
     * @param mixed $target
     * @return
     */
    public function insert_kpi($session_id, $session_year, $assurances, $target) {
        $query = "Insert into `coaudit_refdata` (`coaudit_session_id`, `session_year`, `assurances`, `target`)
            VALUES ('$session_id', '$session_year', '$assurances', '$target')";

        $smt = $this->db->prepare($query);
        $smt->execute();
        $nid = $this->db->lastInsertId();

        //write log
        write_log('admin', $nid, "added kpi");
    }

    /**
     * db_function::update_kpi()
     * update the kpi information
     * @param mixed $session_id
     * @param mixed $session_year
     * @param mixed $assurances
     * @param mixed $target
     * @param mixed $kid
     * @return
     */
    public function update_kpi($session_id, $session_year, $assurances, $target, $kid) {
        $query = "Update `coaudit_refdata` Set `coaudit_session_id` = '$session_id',
            `session_year` = '$session_year',
            `assurances` = '$assurances',
            `target` = '$target'
            WHERE  `coaudit_refdata_id` = $kid";

        $smt = $this->db->prepare($query);
        $smt->execute();

        //write log
        write_log('admin', $kid, "updated kpi");
    }

    //statistics

    /**
     * db_function::get_statisics_sessions()
     * returns active seesions in descending order
     * @return
     */
    public function get_statisics_sessions() {
        $query = "SELECT `coauditsession`.`session_id`,
                    `coauditsession`.`session_name`
                    FROM `coauditdb`.`coauditsession`
                    WHERE `coauditsession`.`active` = 1
                    Order By  `coauditsession`.`from` DESC,  `coauditsession`.`to` DESC";

        $res = $this -> db -> query($query);

        return $res;
    }

    /**
     * db_function::get_statistics_basic()
     * returns the result statistics, if where is given filtered
     * @param string $where
     * @return
     */
    public function get_statistics_basic($where ='') {
        $query = "SELECT `co`.`session_name` AS `Session` , year( `c`.`coauditdate` ) AS `CYear` , `sts`.`topic_no` AS `Topic_No` ,
            `st`.`session_topic` AS `Topic` , sum( `r`.`result` ) AS `res` , count( `r`.`result` ) AS `Total` ,  (sum(`r`.`result`) /  count(`r`.`result`))*100 as `Perc`,
            `st`.`session_topic_id` AS `TopicID` , `r`.`coauditsession_id` AS `SessionID`
            FROM `cacertuser` AS `c` , `result` AS `r` , `session_topic` AS `st` , `coauditsession` AS `co` , `session_topics` AS `sts`
            WHERE `c`.`cacertuser_id` = `r`.`cacertuser_id` AND `r`.`session_topic_id` = `st`.`session_topic_id`
                AND `r`.`coauditsession_id` = `co`.`session_id`
                AND (`sts`.`session_topic_id` = `r`.`session_topic_id` AND `sts`.`coaudit_session_id` = `r`.`coauditsession_id`)
                AND `c`.`deleted` IS Null AND `r`.`deleted` IS Null AND `sts`.`active` = 1 " . $where ."
            GROUP BY `CYear` , `Topic` , `Session` , `TopicID` , `Topic_No` , `SessionID`
            ORDER BY `Session` , `CYear` ,`Topic_No`";
        $res = $this->db->query($query);

        return $res;
    }

    /**
     * db_function::get_statistics_header()
     * returns the result header for the statistics, if where is given filtered
     * @param string $where
     * @return
     */
    public function get_statistics_header($where ='') {
        $query = "SELECT  `sts`.`topic_no` AS `Topic_No` ,
                `st`.`session_topic` AS `Topic` ,
                `st`.`session_topic_id` AS `TopicID`
            FROM   `session_topic` AS `st` , `session_topics` AS `sts`
            WHERE `sts`.`session_topic_id` = `st`.`session_topic_id`
                and `sts`.`active` = 1 " . $where ."
            GROUP BY  `Topic` , `TopicID` , `Topic_No`
            ORDER BY `Topic_No` ";
        $res = $this->db->query($query);

        return $res;
    }

    /**
     * db_function::get_statistics_country()
     * returns the result statistics for the country statistics, if where is given filtered
     * @param string $where
     * @return
     */
    public function get_statistics_country($where ='') {
        $query = "SELECT `co`.`session_name` AS `Session` , year( `c`.`coauditdate` ) AS `CYear` , `sts`.`topic_no` AS `Topic_No` ,
            `st`.`session_topic` AS `Topic` , sum( `r`.`result` ) AS `res` , count( `r`.`session_topic_id` ) AS `Total` ,  (sum(`r`.`result`) /  count(`r`.`result`))*100 as `Perc`,
            `st`.`session_topic_id` AS `TopicID` , `r`.`coauditsession_id` AS `SessionID`,  `c`.`country` AS `Country`
            FROM `cacertuser` AS `c` , `result` AS `r` , `session_topic` AS `st` , `coauditsession` AS `co` , `session_topics` AS `sts`
            WHERE `c`.`cacertuser_id` = `r`.`cacertuser_id` AND `r`.`session_topic_id` = `st`.`session_topic_id`
                AND `r`.`coauditsession_id` = `co`.`session_id`
                AND (`sts`.`session_topic_id` = `r`.`session_topic_id` AND `sts`.`coaudit_session_id` = `r`.`coauditsession_id`)
                AND `c`.`deleted` is Null  AND `r`.`deleted` is Null AND `sts`.`active` = 1 " . $where ."
            GROUP BY `CYear` , `Topic` , `Session` , `TopicID` , `Topic_No` , `SessionID`, `Country`
            ORDER BY `Session` , `CYear`, `Country` ,`Topic_No`";
        $res = $this->db->query($query);

        return $res;
    }

    public function get_session_year($session = 0) {
        $where = '';
        if ($session) {
            $where = ' AND `r`. `coauditsession_id` = ' . intval($session);
        }
        $query = "SELECT Year(`c`.`coauditdate`) AS 'session_year' FROM `cacertuser` as `c`, `result` as `r`
            WHERE `c`.`cacertuser_id` = `r`.`cacertuser_id` " . $where ."
            GROUP BY Year(`c`.`coauditdate`)
        ORDER BY Year(`c`.`coauditdate`)";

        $res = $this -> db -> query($query);
        if($res) {
            return $res->fetchAll();
        } else {
            return false;
        }
    }

    /**
     * db_function::get_statistics_kpi()
     * returns the kpi, if where is given filtered
     * @param string $where
     * @return
     */
    public function get_statistics_kpi($where ='') {
        $query = "Select `session_year`, `assurances`, `target` from `coaudit_refdata` where `coaudit_session_id` = " . $where;

        $res = $this -> db -> query($query);

        return $res;
    }

}
