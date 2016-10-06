<?php

class Enrollments {
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

    function getEnrollmentList() {
    	 $sql = 'SELECT
					id, father_name, father_cell, father_work, father_email,
					mother_name, mother_cell, mother_work, mother_email, address,
					city, zipcode, state, phone, language_primary, language_other,
					emergency_contact1, emergency_phone1, emergency_relation1,
					emergency_contact2, emergency_phone2, emergency_relation2,
					comments, created_on, modified_on, created_by, modified_by,
    	 			physician_name, physician_phone, physician_address, emergency_hospital
				FROM
					school_enrollment
    	 		order by
    	 			created_on desc';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $enrollments = array();
        $stmt->bind_result($id, $father_name, $father_cell, $father_work, $father_email,
				$mother_name, $mother_cell, $mother_work, $mother_email, $address,
				$city, $zipcode, $state, $phone, $language_primary, $language_other,
				$emergency_contact1, $emergency_phone1, $emergency_relation1,
				$emergency_contact2, $emergency_phone2, $emergency_relation2,
				$comments, $created_on, $modified_on, $created_by, $modified_by,
        		$physician_name, $physician_phone, $physician_address, $emergency_hospital);
        while ($stmt->fetch()) {
            $enrollments[] = array('id' => $id, 'father_name' => $father_name,
            	'father_cell' => $father_cell, 'father_work' => $father_work,
            	'father_email' => $father_email, 'mother_name' => $mother_name,
            	'mother_cell' => $mother_cell, 'mother_work' => $mother_work,
            	'mother_email' => $mother_email, 'address' => $address, 'city' => $city,
            	'zipcode' => $zipcode, 'state' => $state, 'phone' => $phone,
            	'language_primary' => $language_primary,
            	'language_other' => $language_other,
            	'emergency_contact1' => $emergency_contact1,
            	'emergency_phone1' => $emergency_phone1,
            	'emergency_relation1' => $emergency_relation1,
            	'emergency_contact2' => $emergency_contact2,
            	'emergency_phone2' => $emergency_phone2,
            	'emergency_relation2' => $emergency_relation2, 'comments' => $comments,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by, 'modified_by' => $modified_by,
            	'physician_name' => $physician_name,
            	'physician_phone' => $physician_phone,
            	'physician_address' => $physician_address,
            	'emergency_hospital' => $emergency_hospital);
        }
		$stmt->close();

        return $enrollments;
    }

    function jsonEnrollment() {
    	//global $select;
    	$jsonData = $this->select->jsonData('enrollment',
    		"select id, concat(father_name,' ', mother_name) value from school_enrollment");
    	Logger::JSON('enrollment',"{".$jsonData."}");
    }

    function iudEnrollment($dml, $id, $father_name, $father_cell, $father_work, $father_email,
				$mother_name, $mother_cell, $mother_work, $mother_email, $address,
				$city, $zipcode, $state, $phone, $language_primary, $language_other,
				$emergency_contact1, $emergency_phone1, $emergency_relation1,
				$emergency_contact2, $emergency_phone2, $emergency_relation2, $comments,
            	$physician_name, $physician_phone, $physician_address, $emergency_hospital) {
        $tableName = 'school_enrollment';
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'father_name'  => array('type' => 's', 'value' => $father_name),
	            'father_cell'     => array('type' => 's', 'value' => $father_cell),
	            'father_work'     => array('type' => 's', 'value' => $father_work),
	            'father_email'     => array('type' => 's', 'value' => $father_email),
	            'mother_name'     => array('type' => 's', 'value' => $mother_name),
	            'mother_cell'     => array('type' => 's', 'value' => $mother_cell),
	            'mother_work'     => array('type' => 's', 'value' => $mother_work),
	            'mother_email'     => array('type' => 's', 'value' => $mother_email),
	            'address'     => array('type' => 's', 'value' => $address),
	            'city'     => array('type' => 's', 'value' => $city),
	            'zipcode'     => array('type' => 's', 'value' => $zipcode),
	            'state'     => array('type' => 's', 'value' => $state),
	            'phone'     => array('type' => 's', 'value' => $phone),
	            'language_primary'     => array('type' => 's', 'value' => $language_primary),
	            'language_other'     => array('type' => 's', 'value' => $language_other),
	            'emergency_contact1'     => array('type' => 's', 'value' => $emergency_contact1),
	            'emergency_phone1'     => array('type' => 's', 'value' => $emergency_phone1),
	            'emergency_relation1'     => array('type' => 's', 'value' => $emergency_relation1),
	            'emergency_contact2'     => array('type' => 's', 'value' => $emergency_contact2),
	            'emergency_phone2'     => array('type' => 's', 'value' => $emergency_phone2),
	            'emergency_relation2'     => array('type' => 's', 'value' => $emergency_relation2),
	            'comments'     => array('type' => 's', 'value' => $comments),
	            'modified_by'   => array('type' => 'i', 'value' => $login_id),
        		'physician_name' => array('type' => 's', 'value' => $physician_name),
            	'physician_phone' => array('type' => 's', 'value' => $physician_phone),
            	'physician_address' => array('type' => 's', 'value' => $physician_address),
                'emergency_hospital' => array('type' => 's', 'value' => $emergency_hospital)
	        );
        }

       	if ($dml=='i') {
       		$data['created_by'] = array('type' => 'i', 'value' => $login_id);
       		$id = $this->db->insert($tableName, $data);
       	}
       	else if ($dml=='u') $this->db->update($tableName, $id, $data);
	   	else if ($dml=='d') $this->db->delete($tableName, $id);
	   	Enrollments::jsonEnrollment();
        return $id;
    }
}

?>