<?php

class Donators {
    private $db;
    private $conn;
	private $select;

    function __construct(&$db) {
    	$this->common = new Commons();
    	$this->select = new Selects($db);
        $this->db = $db;
        if (!$this->db) {
            $this->db = new Db();
        }

        $this->conn = $this->db->getConnection();
    }

    function getDonatorList() {
    	 $sql = 'SELECT
					id, name, address1, address2, city, state, zipcode, email,
        			phone, company_name, comments, created_on, modified_on,
        			created_by, modified_by
				FROM
					donator
    	 		order by
    	 			created_on desc';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $donators = array();
        $stmt->bind_result($id, $name, $address1, $address2, $city, $state,	$zipcode,
        		$email, $phone,	$company_name, $comments, $created_on, $modified_on,
        		$created_by, $modified_by);
        while ($stmt->fetch()) {
            $donators[] = array('id' => $id, 'name' => $name, 'address1' => $address1,
            	'address2' => $address2, 'city' => $city, 'state' => $state,
            	'zipcode' => $zipcode, 'email' => $email, 'phone' => $phone,
            	'company_name' => $company_name, 'comments' => $comments,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by, 'modified_by' => $modified_by);
        }
		$stmt->close();

        return $donators;
    }

    function jsonDonator() {
    	//global $select;
    	$jsonData = $this->select->jsonData('donator', 'select id, name value from donator');
    	Logger::JSON('donator',"{".$jsonData."}");
    }

    function iudDonator($dml, $id, $name, $address1, $address2, $city, $state,
    		$zipcode, $email, $phone, $company_name, $comments) {
        $tableName = 'donator';
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'name'  => array('type' => 's', 'value' => $name),
	            'address1'     => array('type' => 's', 'value' => $address1),
	            'address2'     => array('type' => 's', 'value' => $address2),
	            'city'     => array('type' => 's', 'value' => $city),
	            'state'     => array('type' => 's', 'value' => $state),
	            'zipcode'     => array('type' => 's', 'value' => $zipcode),
	            'email'     => array('type' => 's', 'value' => $email),
	            'phone'     => array('type' => 's', 'value' => $phone),
	            'company_name'     => array('type' => 's', 'value' => $company_name),
	            'comments'     => array('type' => 's', 'value' => $comments),
	            'modified_by'   => array('type' => 'i', 'value' => $login_id)
	        );
        }

       	if ($dml=='i') {
       		$data['created_by'] = array('type' => 'i', 'value' => $login_id);
       		$id = $this->db->insert($tableName, $data);
       	}
       	else if ($dml=='u') $this->db->update($tableName, $id, $data);
	   	else if ($dml=='d') $this->db->delete($tableName, $id);
	   	Donators::jsonDonator();
        return $id;
    }
}

?>