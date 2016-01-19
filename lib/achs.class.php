<?php

class ACHs {
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

    function getDonatorsACHList($donatorid) {
    	$sql = 'SELECT
			a.id, a.donator_id, a.ach_date, a.bank_name, a.routing_number,
			a.account_number, a.void_check_included, a.cycle, a.amount,
			a.start_date, a.end_date, a.comments, a.created_on,
    		a.modified_on, a.created_by, a.modified_by
		FROM
			ach a
	   	WHERE
    		a.donator_id = ?';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $donatorid);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();

    	$achs = array();
    	$stmt->bind_result($id, $donator_id, $ach_date, $bank_name,
    		$routing_number, $account_number, $void_check_included, $cycle,
    		$amount, $start_date, $end_date, $comments, $created_on,
			$modified_on, $created_by, $modified_by);
    	while ($stmt->fetch()) {
    		$achs[] = array('id' => $id,
    				'donator_id' => $donator_id,
    				'ach_date' => Commons::date_format_form($ach_date),
    				'bank_name' => $bank_name,
    				'routing_number' => $routing_number,
    				'account_number' => $account_number,
    				'void_check_included' => ($void_check_included==1?'Yes':'No'),
            		'cycle' => $cycle,
    				'amount' => $amount,
    				'start_date' => Commons::date_format_form($start_date),
    				'end_date' => Commons::date_format_form($end_date),
    				'comments' => $comments,
    				'created_on' => Commons::date_format_form($created_on),
    				'modified_on' => Commons::date_format_form($modified_on),
    				'created_by' => $created_by,
    				'modified_by' => $modified_by);
    	}
    	$stmt->close();

    	return $achs;
    }

    function iudACH($dml, $id, $donator_id, $ach_date, $bank_name,
    		$routing_number, $account_number, $void_check_included, $cycle,
    		$amount, $start_date, $end_date, $comments) {
        $tableName ='ach';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'donator_id'     => array('type' => 'i', 'value' => $donator_id),
	            'ach_date'  => array('type' => 's', 'value' => Commons::date_format_sql($ach_date)),
	            'bank_name'  => array('type' => 's', 'value' => $bank_name),
	            'routing_number'  => array('type' => 's', 'value' => $routing_number),
	            'account_number'  => array('type' => 's', 'value' => $account_number),
	            'void_check_included'     => array('type' => 'i', 'value' => $void_check_included),
	        	'cycle'  => array('type' => 's', 'value' => $cycle),
	            'amount'     => array('type' => 'd', 'value' => $amount),
	            'start_date'  => array('type' => 's', 'value' => Commons::date_format_sql($start_date)),
	            'end_date'  => array('type' => 's', 'value' => Commons::date_format_sql($end_date)),
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