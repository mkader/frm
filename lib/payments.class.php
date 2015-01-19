<?php

class Payments {
    private $db;

    private $conn;

	private $session;
	
	private $common;

    function __construct(&$db) {
    	$this->session = new Sessions();
    	$this->common = new Commons();
        $this->db = $db;
        if (!$this->db) {
            $this->db = new Db();
        }

        $this->conn = $this->db->getConnection();
    }

    function getDonatorsPaymentList($donatorid) {
    	global $common;
    	$sql = 'SELECT
        	p.id, p.payment_date, p.amount, p.payment_method_id, p.tax_year, 
    		p.donator_id, p.pledge_id, p.comments, pm.payment_method,
    		p.created_on, p.modified_on, p.created_by,  p.modified_by,
    		e.title
        FROM
        	payment p
        	inner join donator d on d.id = p.donator_id
        	inner join payment_method pm on pm.id = p.payment_method_id
    		inner join pledge pl on pl.id = p.pledge_id
    		inner join event e on e.id = pl.event_id
       WHERE
    		p.donator_id = ?';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $donatorid);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    	
    	$payements = array();
    	$stmt->bind_result($id, $payment_date, $amount, $payment_method_id, 
    		$tax_year, $donator_id, $pledge_id, $comments, $payment_method, 
    		$created_on, $modified_on, $created_by, $modified_by, $title);
    	while ($stmt->fetch()) {
    		$payements[] = array('id' => $id, 
    				'payment_date' => $common->date_format_form($payment_date),
    				'amount' => $amount,
    				'payment_method_id' => $payment_method_id,
    				'tax_year' => $tax_year,
    				'donator_id' => $donator_id,
    				'title' => $title,
    				'pledge_id' => $pledge_id,
    				'comments' => $comments,
    				'created_on' => $common->date_format_form($created_on),
    				'modified_on' => $common->date_format_form($modified_on),
    				'created_by' => $created_by,
    				'modified_by' => $modified_by ,
    				'payment_method' => $payment_method);
    	}
    	$stmt->close();
    
    	return $payements;
    }
    

    /*payment_date`, amount`, payment_method_id`, tax_year`,
     donator_id`, pledge_id`, comments`
    
    */

    function iudPayment($dml, $id, $payment_date, $amount, $payment_method_id, 
    		$tax_year, $donator_id,  $pledge_id, $comments) {
        global $session, $common;
        $tableName ='payment';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval($session->loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'payment_date'  => array('type' => 's', 'value' => $common->date_format_sql($payment_date)),
	            'amount'     => array('type' => 'd', 'value' => $amount),
	            'payment_method_id'     => array('type' => 'i', 'value' => $payment_method_id),
	            'tax_year'     => array('type' => 'i', 'value' => $tax_year),
	            'donator_id'     => array('type' => 'i', 'value' => $donator_id),
	            'pledge_id'     => array('type' => 'i', 'value' => $pledge_id),
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