<?php

class Masjids {
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

    function getMasjidList() {
    	 $sql = 'SELECT
					id, name, address, city, state, zipcode, email, phone,
					contact_name, contact_phone, contact_email, comments,
					website, created_on, modified_on, created_by, modified_by
				FROM
					masjid
				where
					masjid_type_id = 1
    	 		order by
    	 			created_on desc';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $masjids = array();
        $stmt->bind_result($id, $name, $address, $city, $state,	$zipcode, $email,
        		$phone,	$contact_name, $contact_phone, $contact_email, $comments,
        		$website, $created_on, $modified_on, $created_by, $modified_by);
        while ($stmt->fetch()) {
            $masjids[] = array('id' => $id, 'name' => $name, 'address' => $address,
            	'city' => $city, 'state' => $state,
            	'zipcode' => $zipcode, 'email' => $email, 'phone' => $phone,
            	'contact_name' => $contact_name,
            	'contact_phone' => $contact_phone,
            	'contact_email' => $contact_email,
            	'comments' => $comments,
            	'website' => $website,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by, 'modified_by' => $modified_by);
        }
		$stmt->close();

        return $masjids;
    }

    function iudMasjid($dml, $id, $name, $address, $city, $state,
    		$zipcode, $email, $phone, $contact_name, $contact_phone,
    		$contact_email, $comments, $website) {
        $tableName = 'masjid';
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'name'  => array('type' => 's', 'value' => $name),
	            'address'     => array('type' => 's', 'value' => $address),
	            'city'     => array('type' => 's', 'value' => $city),
	            'state'     => array('type' => 's', 'value' => $state),
	            'zipcode'     => array('type' => 's', 'value' => $zipcode),
	            'email'     => array('type' => 's', 'value' => $email),
	            'phone'     => array('type' => 's', 'value' => $phone),
	            'contact_name'     => array('type' => 's', 'value' => $contact_name),
	            'contact_phone'     => array('type' => 's', 'value' => $contact_phone),
	            'contact_email'     => array('type' => 's', 'value' => $contact_email),
	            'comments'     => array('type' => 's', 'value' => $comments),
        		'website'     => array('type' => 's', 'value' => $website),
	            'modified_by'   => array('type' => 'i', 'value' => $login_id)
	        );
        }

       	if ($dml=='i') {
       		$data['created_by'] = array('type' => 'i', 'value' => $login_id);
       		$id = $this->db->insert($tableName, $data);
       	}
       	else if ($dml=='u') $this->db->update($tableName, $id, $data);
	   	else if ($dml=='d') $this->db->delete($tableName, $id);
	    return $id;
    }
}

?>