<?php

class Users {
    private $db;

    private $conn;

	private $session;

    function __construct(&$db) {
    	$this->session = new Sessions();
        $this->db = $db;
        if (!$this->db) {
            $this->db = new Db();
        }

        $this->conn = $this->db->getConnection();
    }

	/*
	SELECT
		id, name, password, email, phone, user_type_id, active, created_on, modified_on, created_by, modified_by
	FROM
		user;
	*/

    function login($username, $password) {
        $encPassword = sha1(AUTH_SALT.$password);
		$sql = 'SELECT id, name, phone, user_type_id FROM user WHERE active = 1 AND email = ? AND password = ?';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->bind_param('ss', $username, $encPassword);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $stmt->bind_result($id, $name, $phone, $user_type_id);
        $stmt->fetch();
        $stmt->close();

        if ($id <= 0) {
            Logger::log('Matching user not found for username/password combination [' . $username . '/' . $encPassword . ']');
            return false;
        }

        // Return the user ID, the generated auth token, and admin privileges if specified
        return array(
            'id' 		=> $id,
            'name' 			=> $name,
            'phone'   		=> $phone,
            'user_type_id' 	=> $user_type_id,
        );
    }
    
    function isUserExists($user_id, $email) {
    	$sql = 'SELECT id FROM user WHERE id != ? and email = ?';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('is', $user_id, $email);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$stmt->bind_result($id);
    	$stmt->fetch();
    	$stmt->close();
    
    	if ($id > 0) {
    		Logger::log('User alread found for email/id combination [' . $email . '/' . $id . ']');
    		return true;
    	}
    	
    	return false;
    }

    /*function getProfileForUsername($username) {
        $sql = 'SELECT id, email FROM user WHERE username = ?';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->bind_param('s', $username);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $stmt->bind_result($userId, $email);
        $stmt->fetch();
        $stmt->close();

        $sql = 'SELECT p.id, content, t.title FROM posts p JOIN threads t ON p.thread_id = t.id WHERE p.id = ?';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->bind_param('i', $userId);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $posts = array();
        $stmt->bind_result($postId, $content, $threadTitle);
        while ($stmt->fetch()) {
            $posts[] = array('id' => $postId, 'title' => $threadTitle, 'postContent' => $content);
        }

        return array(
            'id' => $userId,
            'username' => $username,
            'email' => $email,
            'posts' => $posts
        );
    }*/

	function getUserList() {
	        $sql = 'SELECT
	        	u.id, name, email, password, phone, u.user_type_id, active,
	        	created_on, modified_on, created_by, modified_by, ut.user_type
	        FROM
	        	user u
	        	inner join user_type ut on u.user_type_id = ut.id';
	        $stmt = $this->conn->prepare($sql);
	        $this->db->checkError();
	        $stmt->execute();
	        $this->db->checkError();


	        $users = array();
	        $stmt->bind_result($id, $name, $email, $password, $phone, $user_type_id,
	        	$active, $created_on, $modified_on, $created_by, $modified_by, $user_type);
	        while ($stmt->fetch()) {
	            $users[] = array('id' => $id, 'name' => $name, 'email' => $email, 'password' => $password,
	            	'phone' => $phone, 'user_type_id' => $user_type_id, 'active' => ($active==1?'Yes':'No'),
	            	'created_on' => $created_on, 'modified_on' => $modified_on,
	            	'created_by' => $created_by, 'modified_by' => $modified_by , 'user_type' => $user_type);
	        }
			$stmt->fetch();
			$stmt->close();

	        return $users;
    }

    function iudUser($dml, $id, $name, $email, $password, $phone, $user_type_id, $active) {
        global $session;
        $tableName = 'user';
        $timestamp = date('Y-m-d H:i:s');
        $login_id = @intval($session->loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'name'  => array('type' => 's', 'value' => $name),
	            'phone'     => array('type' => 's', 'value' => $phone),
	            'email'     => array('type' => 's', 'value' => $email),
	            'user_type_id'     => array('type' => 'i', 'value' => $user_type_id),
	            'active'     => array('type' => 'i', 'value' => $active),
	            'modified_by'   => array('type' => 'i', 'value' => $login_id)/*,
	            'modified_on'  => array('type' => 's', 'value' => $timestamp)*/
	        );
        }
        
       	if ($dml=='i') {
       		$data['password'] = array('type' => 's', 'value' => sha1(AUTH_SALT.$password));
       		$data['created_by'] = array('type' => 'i', 'value' => $login_id);
       		//$data['created_on'] =  array('type' => 's', 'value' => $timestamp);
       		$id = $this->db->insert($tableName, $data);
       	} else if ($dml=='u') {
	   		if (strlen(trim($password)) > 0)
	   			$data['password'] = array('type' => 's', 'value' => sha1(AUTH_SALT.$password));
       		$this->db->update($tableName, $id, $data);
	   	} else if ($dml=='d') $this->db->delete($tableName, $id);

        return $id;
    }
}

?>