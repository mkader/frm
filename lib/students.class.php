<?php

class Students {
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

	function getStudentList() {
    	$sql = 'SELECT
    		s.id, s.enrollment_id, s.first_name, s.middle_name, s.last_name, s.gender,
    		s.dob, s.age, s.public_school_grade, s.medical_conditions, s.allergies_details, 
    		s.reading_level_arabic, s.reading_level_quran, s.date_of_join, s.comments,
    		s.active, s.created_on, s.modified_on, s.created_by, s.modified_by, 
    		concat(e.father_name,\' \', e.mother_name) parent_name
    	FROM
        	school_student s
        	inner join school_enrollment e on e.id = s.enrollment_id';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $students = array();
        $stmt->bind_result($id, $enrollment_id, $first_name, $middle_name, $last_name, $gender,
			$dob, $age, $public_school_grade, $medical_conditions, $allergies_details, 
			$reading_level_arabic, $reading_level_quran, $date_of_join, $comments,
			$active, $created_on, $modified_on, $created_by, $modified_by, $parent_name);
        while ($stmt->fetch()) {
            $students[] = array('id' => $id, 'enrollment_id' => $enrollment_id,
            	'first_name' => $first_name, 
            	'middle_name' => $middle_name===NULL?"":$middle_name,
            	'last_name' => $last_name,  'gender' => $gender,
            	'dob' => Commons::date_format_form($dob), 'age' => $age, 
            	'public_school_grade' => $public_school_grade,
            	'medical_conditions' => $medical_conditions,
            	'allergies_details' => $allergies_details,
            	'reading_level_arabic' => $reading_level_arabic,
            	'reading_level_quran' => $reading_level_quran,
            	'date_of_join' => Commons::date_format_form($date_of_join),
            	'comments' => $comments,
            	'active' => $active,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by,
            	'modified_by' => $modified_by,
            	'parent_name' => $parent_name);
        }
		$stmt->close();

        return $students;
    }

    function getEnrollmentStudentList($enrollmentid) {
    	$sql = 'SELECT
    		s.id, s.enrollment_id, s.first_name, s.middle_name, s.last_name, s.gender,
    		s.dob, s.age, s.public_school_grade, s.medical_conditions, s.allergies_details, 
    		s.reading_level_arabic, s.reading_level_quran, s.date_of_join, s.comments,
    		s.active, s.created_on, s.modified_on, s.created_by, s.modified_by,
    		concat(e.father_name,\' \', e.mother_name) parent_name
    	FROM
        	school_student s
        	inner join school_enrollment e on e.id = s.enrollment_id
    	WHERE
    		s.enrollment_id = ?';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $enrollmentid);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();

    	$students = array();
        $stmt->bind_result($id, $enrollment_id, $first_name, $middle_name, $last_name, $gender,
			$dob, $age, $public_school_grade, $medical_conditions, $allergies_details, 
			$reading_level_arabic, $reading_level_quran, $date_of_join, $comments,
			$active, $created_on, $modified_on, $created_by, $modified_by, $parent_name);
        while ($stmt->fetch()) {
            $students[] = array('id' => $id, 'enrollment_id' => $enrollment_id,
            	'first_name' => $first_name, 
            	'middle_name' => $middle_name===NULL?"":$middle_name,
            	'last_name' => $last_name,  'gender' => $gender,
            	'dob' => Commons::date_format_form($dob), 'age' => $age, 
            	'public_school_grade' => $public_school_grade,
            	'medical_conditions' => $medical_conditions,
            	'allergies_details' => $allergies_details,
            	'reading_level_arabic' => $reading_level_arabic,
            	'reading_level_quran' => $reading_level_quran,
            	'date_of_join' => Commons::date_format_form($date_of_join),
            	'comments' => $comments,
            	'active' => $active,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by,
            	'modified_by' => $modified_by,
            	'parent_name' => $parent_name);
        }

    	return $students;
    }

    function getStudentJSONList($enrollmentid) {
    	//global $select;
    	$sql  ='SELECT
        	s.id,  (s.first_name,\' \', s.middle_name,\' \', s.last_name) value
        FROM
        	school_student s
        	inner join school_enrollment e on e.id = s.enrollment_id
    	WHERE
    		e.enrollment_id = '.$enrollmentid;
    	$jsonData = $this->select->jsonData('student', $sql);
    	return $jsonData;
    }

    function iudStudent($dml, $id, $enrollment_id, $first_name, $middle_name, $last_name, $gender,
			$dob, $age, $public_school_grade, $medical_conditions, $allergies_details, 
			$reading_level_arabic, $reading_level_quran, $date_of_join, $comments, $active) {
        $tableName ='school_student';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'enrollment_id'     => array('type' => 'i', 'value' => $enrollment_id),
	            'first_name'     => array('type' => 's', 'value' => $first_name),
    			'middle_name'     => array('type' => 's', 'value' => $middle_name),
    			'last_name'     => array('type' => 's', 'value' => $last_name),
    			'gender'     => array('type' => 's', 'value' => $gender),
    			'dob'     => array('type' => 's', 'value' => Commons::date_format_sql($dob)),
    			'age'     => array('type' => 'i', 'value' => $age),
    			'public_school_grade'     => array('type' => 's', 'value' => $public_school_grade),
    			'medical_conditions'     => array('type' => 's', 'value' => $medical_conditions),
    			'allergies_details'     => array('type' => 's', 'value' => $allergies_details),
    			'reading_level_arabic'     => array('type' => 's', 'value' => $reading_level_arabic),
    			'reading_level_quran'     => array('type' => 's', 'value' => $reading_level_quran),
    			'date_of_join'     => array('type' => 's', 'value' => Commons::date_format_sql($date_of_join)),
    			'comments'     => array('type' => 's', 'value' => $comments),
    			'active'     => array('type' => 'i', 'value' => $active),
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