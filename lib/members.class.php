<?php

class Members {
    private $db;
    private $conn;

    function __construct(&$db) {
    	$this->db = $db;
        if (!$this->db) {
            $this->db = new Db();
        }

        $this->conn = $this->db->getConnection();
    }

	function getMemberList() {
	        $sql = 'SELECT
	        	u.id, name, email, phone, active,
	        	created_on, modified_on, created_by, modified_by
	        FROM
	        	member u';
	        $stmt = $this->conn->prepare($sql);
	        $this->db->checkError();
	        $stmt->execute();
	        $this->db->checkError();

	        $members = array();
	        $stmt->bind_result($id, $name, $email, $phone, $active,
	        	$created_on, $modified_on, $created_by, $modified_by);
	        while ($stmt->fetch()) {
	            $members[] = array('id' => $id, 'name' => $name, 'email' => $email,
	            	'phone' => $phone, 'active' => ($active==1?'Yes':'No'),
	            	'created_on' => $created_on, 'modified_on' => $modified_on,
	            	'created_by' => $created_by, 'modified_by' => $modified_by);
	        }
			$stmt->close();

	        return $members;
    }

    function iudMember($dml, $id, $name, $email, $phone, $active) {
        $tableName = 'member';
        $timestamp = date('Y-m-d H:i:s');
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'name'  => array('type' => 's', 'value' => $name),
	            'phone'     => array('type' => 's', 'value' => $phone),
	            'email'     => array('type' => 's', 'value' => $email),
	            'active'     => array('type' => 'i', 'value' => $active),
	            'modified_by'   => array('type' => 'i', 'value' => $login_id)/*,
	            'modified_on'  => array('type' => 's', 'value' => $timestamp)*/
	        );
        }
        if ($dml=='i') {
       		$data['created_by'] = array('type' => 'i', 'value' => $login_id);
       		//$data['created_on'] =  array('type' => 's', 'value' => $timestamp);
       		$id = $this->db->insert($tableName, $data);
       	} else if ($dml=='u') {
	   		$this->db->update($tableName, $id, $data);
	   	} else if ($dml=='d') $this->db->delete($tableName, $id);

        return $id;
    }
}

?>