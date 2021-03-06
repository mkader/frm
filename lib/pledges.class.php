<?php

class Pledges {
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

	function getPledgeList() {
    	$sql = 'SELECT
        	p.id, p.event_id, p.donator_id, amount, p.payment_method_id,
        	p.payment_type_id, e.title, d.name, pm.payment_method,
        	pt.payment_type, p.created_on, p.modified_on,
        	p.created_by,  p.modified_by
        FROM
        	pledge p
        	inner join event e on e.id = p.event_id
        	inner join donator d on d.id = p.donator_id
        	inner join payment_method pm on pm.id = p.payment_method_id
        	inner join payment_type pt on pt.id = p.payment_type_id';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();


        $pledges = array();
        $stmt->bind_result($id, $event_id, $donator_id, $amount, $payment_method_id,
        	$payment_type_id, $title, $name, $payment_method, $payment_type,
        	$created_on, $modified_on, $created_by, $modified_by);
        while ($stmt->fetch()) {
            $pledges[] = array('id' => $id, 'event_id' => $event_id,
            	'donator_id' => $donator_id,
            	'amount' => $amount,
            	'payment_method_id' => $payment_method_id,
            	'payment_type_id' => $payment_type_id,
            	'title' => $title,
            	'name' => $name,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by,
            	'modified_by' => $modified_by ,
            	'payment_method' => $payment_method,
            	'payment_type' => $payment_type);
        }
		$stmt->close();

        return $pledges;
    }

    function getDonatorsPledgeList($donatorid) {
    	$sql = 'SELECT
        	p.id, p.event_id, p.donator_id, amount, e.title, d.name,
        	p.created_on, p.modified_on,
        	p.created_by,  p.modified_by,p2.paid
        FROM
        	pledge p
        	inner join event e on e.id = p.event_id
        	inner join donator d on d.id = p.donator_id
        	left join (SELECT pledge_id, donator_id, SUM(amount) paid FROM payment group by pledge_id, donator_id) p2 on
				p2.pledge_id  = p.id and p2.donator_id = d.id
    	WHERE
    		p.donator_id = ?';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $donatorid);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();

    	$pledges = array();
    	$stmt->bind_result($id, $event_id, $donator_id, $amount, $title, $name,
    			$created_on, $modified_on, $created_by, $modified_by, $paid);
    	while ($stmt->fetch()) {
    		$pledges[] = array('id' => $id, 'event_id' => $event_id,
    				'donator_id' => $donator_id,
    				'amount' => $amount,
    				'title' => $title,
    				'name' => $name,
    				'created_on' => Commons::date_format_form($created_on),
    				'modified_on' => Commons::date_format_form($modified_on),
    				'created_by' => $created_by,
    				'modified_by' => $modified_by ,
    				'paid' => $paid);
    	}
    	$stmt->close();

    	return $pledges;
    }

    function getDonatorsPledgeJSONList($donatorid) {
    	//global $select;
    	$sql  ='SELECT
        	p.id,  e.title value
        FROM
        	pledge p
        	inner join event e on e.id = p.event_id
        	inner join donator d on d.id = p.donator_id
    	WHERE
    		p.donator_id = '.$donatorid;
    	$jsonData = $this->select->jsonData('pledge', $sql);
    	return $jsonData;
    }

    function iudPledge($dml, $id, $event_id, $donator_id, $amount) {
        $tableName ='pledge';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'event_id'  => array('type' => 'i', 'value' => $event_id),
	            'donator_id'     => array('type' => 'i', 'value' => $donator_id),
	            'amount'     => array('type' => 'd', 'value' => $amount),
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