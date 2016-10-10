<?php

class Fees {
    private $db;
    private $conn;

    function __construct(&$db) {
    	$this->common = new Commons();
        $this->db = $db;
        if (!$this->db) {
            $this->db = new Db();
        }

        $this->conn = $this->db->getConnection();
    }

    function getEnrollmentFeeList($enrollmentid) {
    	$sql = 'SELECT
        	p.id, p.fee_date, p.amount, p.fee_method_id,
    		p.enrollment_id, p.event_id, p.comments, pm.payment_method fee_method,
    		p.created_on, p.modified_on, p.created_by,  p.modified_by,
    		ev.title, concat(en.father_name,\' \',en.mother_name) parent_name 
        FROM
        	school_fee p
        	inner join payment_method pm on pm.id = p.fee_method_id
    		left join school_enrollment en on en.id = p.enrollment_id
    		left join event ev on ev.id = p.event_id
       	WHERE
    		p.enrollment_id = ?
    	ORDER BY
    		p.fee_date DESC';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $enrollmentid);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();

    	$payements = array();
    	$stmt->bind_result($id, $fee_date, $amount, $fee_method_id,
    		$enrollment_id, $event_id, $comments, $fee_method,
    		$created_on, $modified_on, $created_by, $modified_by, $title, $parent_name);
    	while ($stmt->fetch()) {
    		$payements[] = array('id' => $id,
    				'fee_date' => Commons::date_format_form($fee_date),
    				'amount' => $amount,
    				'fee_method_id' => $fee_method_id,
    				'enrollment_id' => $enrollment_id,
    				'title' => $title===NULL?"":$title,
    				'event_id' => $event_id,
    				'comments' => $comments,
    				'created_on' => Commons::date_format_form($created_on),
    				'modified_on' => Commons::date_format_form($modified_on),
    				'created_by' => $created_by,
    				'modified_by' => $modified_by ,
    				'fee_method' => $fee_method,
    				'parent_name' => $parent_name);
    	}
    	$stmt->close();

    	return $payements;
    }

	function iudFee($dml, $id, $fee_date, $amount, $fee_method_id,
    		$enrollment_id,  $event_id, $comments) {
        $tableName ='school_fee';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'fee_date'  => array('type' => 's', 'value' => Commons::date_format_sql($fee_date)),
	            'amount'     => array('type' => 'i', 'value' => $amount),
	            'fee_method_id'     => array('type' => 'i', 'value' => $fee_method_id),
	            'enrollment_id'     => array('type' => 'i', 'value' => $enrollment_id),
	            'event_id'     => array('type' => 'i', 'value' => $event_id),
	            'comments'  => array('type' => 's', 'value' => $comments),
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