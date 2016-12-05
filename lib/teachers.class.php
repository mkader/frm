<?php

class Teachers {
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

    function getTeacherList() {
    	 $sql = 'SELECT
					id, name, address, city, zipcode, state, phone, email, 
    	 			comments, ssn, active, join_date, resign_date, full_time, 
    	 			fee_deduction, volunteer,  
    	 			created_on, modified_on, created_by, modified_by
    	 		FROM
					school_teacher
    	 		order by modified_on desc';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $teachers = array();
        $stmt->bind_result($id, $name, $address, $city, $zipcode, $state, 
        		$phone, $email, $comments, 	$ssn, $active, $join_date, 
        		$resign_date, $full_time, $fee_deduction, $volunteer, 
        		$created_on, $modified_on, $created_by, $modified_by);
        while ($stmt->fetch()) {
            $teachers[] = array('id' => $id, 'name' => $name,
            	'address' => $address===NULL?"":$address, 
            	'city' => $city===NULL?"":$city,
            	'zipcode' => $zipcode===NULL?"":$zipcode, 
            	'state' => $state, 
            	'phone' => $phone===NULL?"":$phone,
            	'email' => $email===NULL?"":$email,
            	'comments' => $comments, 'ssn' => $ssn, 
            	'active' => $active,
            	'volunteer' => $volunteer,
            	'join_date' => Commons::date_format_form($join_date),
            	'resign_date' => Commons::date_format_form($resign_date),
            	'full_time' => $full_time,
            	'fee_deduction' => $fee_deduction,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by, 'modified_by' => $modified_by);
        }
		$stmt->close();

        return $teachers;
    }

    function jsonTeacher() {
    	//global $select;
    	$jsonData = $this->select->jsonData('teacher',
    		"select id, name value from school_teacher");
    	Logger::JSON('teacher',"{".$jsonData."}");
    }

    function iudTeacher($dml, $id, $name, $address, $city, $zipcode, $state,
				$phone, $email, $comments, $ssn, $join_date, $resign_date, 
    			$full_time, $active, $fee_deduction, $volunteer) {
        $tableName = 'school_teacher';
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'name'  => array('type' => 's', 'value' => $name),
	            'address'     => array('type' => 's', 'value' => $address),
	            'city'     => array('type' => 's', 'value' => $city),
	            'zipcode'     => array('type' => 's', 'value' => $zipcode),
	            'state'     => array('type' => 's', 'value' => $state),
	            'phone'     => array('type' => 's', 'value' => $phone),
	            'email'     => array('type' => 's', 'value' => $email),
	            'comments'     => array('type' => 's', 'value' => $comments),
	            'ssn'     => array('type' => 's', 'value' => $ssn),
	            'modified_by'   => array('type' => 'i', 'value' => $login_id),
        		'join_date'     => array('type' => 's', 'value' => Commons::date_format_sql($join_date)),
    			'resign_date'     => array('type' => 's', 'value' => Commons::date_format_sql($resign_date)),
    			'fee_deduction' => array('type' => 'i', 'value' => $fee_deduction),
        		'active' => array('type' => 'i', 'value' => $active),
        		'volunteer' => array('type' => 'i', 'value' => $volunteer),
        		'full_time' => array('type' => 'i', 'value' => $full_time)
	        );
        }

       	if ($dml=='i') {
       		$data['created_by'] = array('type' => 'i', 'value' => $login_id);
       		$id = $this->db->insert($tableName, $data);
       	}
       	else if ($dml=='u') $this->db->update($tableName, $id, $data);
	   	else if ($dml=='d') $this->db->delete($tableName, $id);
	   	Teachers::jsonTeacher();
        return $id;
    }
    
    function getTeacherAttendanceList($teacherid) {
   		$sql = 'SELECT
    		id, school_teacher_id, attendance_date, time_in, time_out, hours
    	FROM
        	school_teacher_attendance
    	WHERE
    		school_teacher_id = ?
   		order by
   			attendance_date desc';
    		
   		$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $teacherid);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$attendances = array();
    	$stmt->bind_result($id, $school_teacher_id, $attendance_date, 
    			$time_in, $time_out, $hours);
    	while ($stmt->fetch()) {
    		$attendances[] = array('id' => $id,
    			'student_teacher_id' => $school_teacher_id, 
    			'attendance_date' => Commons::date_format_form($attendance_date),
    			'time_in' => $time_in,
    			'time_out' => $time_out,
    			'hours' => $hours);
    	}

    	return $attendances;
    }
    
	function iudAttendance($dml, $id, $school_teacher_id, $attendance_date, 
    		$time_in, $time_out, $hours) {
    	$tableName ='school_teacher_attendance';
    	$login_id = @intval(Sessions::loginUserID());
    	if ($dml!='d') {
    		$data = array(
				'school_teacher_id'     => array('type' => 'i', 'value' => $school_teacher_id),
   				'attendance_date'     => array('type' => 's', 'value' => Commons::date_format_sql($attendance_date)),
   				'time_in'     => array('type' => 's', 'value' => $time_in),
   				'time_out'     => array('type' => 's', 'value' => $time_out),
  				'hours'     => array('type' => 'd', 'value' => $hours)
    		);
    	}
    
    	if ($dml=='i') $id = $this->db->insert($tableName, $data);
    	else if ($dml=='u') $this->db->update($tableName, $id, $data);
    	else if ($dml=='d') $this->db->delete($tableName, $id);

    	return $id;
    }
    	
    function getTeacherSalaryList($teacherid) {
    	$sql = 'SELECT
    		id, school_teacher_id, salary_date, worked_hours, 
    		total_salary, deduction, payment
    	FROM
        	school_teacher_salary
    	WHERE
    		school_teacher_id = ?
   		order by
   			salary_date desc';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $teacherid);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    	
    	$salarys = array();
    	$stmt->bind_result($id, $school_teacher_id, $salary_date, $worked_hours, 
    		$total_salary, $deduction, $payment);
    	while ($stmt->fetch()) {
    		$salarys[] = array('id' => $id,
    			'student_teacher_id' => $school_teacher_id,
    			'salary_date' => Commons::date_format_form($salary_date),
    			'worked_hours' => $worked_hours,
    			'total_salary' => $total_salary,
    			'deduction' => $deduction,
    			'payment' => $payment);
    	}
    	
    	return $salarys;
    }
    	
   	function iudSalary($dml, $id, $school_teacher_id, $salary_date, $worked_hours, 
    		$total_salary, $deduction, $payment) {
   		$tableName ='school_teacher_salary';
   		$login_id = @intval(Sessions::loginUserID());
   		if ($dml!='d') {
   			$data = array(
				'school_teacher_id'     => array('type' => 'i', 'value' => $school_teacher_id),
   				'salary_date'     => array('type' => 's', 'value' => Commons::date_format_sql($salary_date)),
    			'worked_hours'     => array('type' => 'd', 'value' => $worked_hours),
    			'total_salary'     => array('type' => 'd', 'value' => $total_salary),
    			'deduction'     => array('type' => 'd', 'value' => $deduction),
   				'payment'     => array('type' => 'd', 'value' => $payment)
    		);
    	}
    	
    	if ($dml=='i') $id = $this->db->insert($tableName, $data);
    	else if ($dml=='u') $this->db->update($tableName, $id, $data);
    	else if ($dml=='d') $this->db->delete($tableName, $id);
    	
    	return $id;
    }
}

?>