<?php

class Selects {
    private $db;

    private $conn;

    function __construct(&$db) {
        $this->db = $db;
        if (!$this->db) {
            $this->db = new Db();
        }

        $this->conn = $this->db->getConnection();
    }

	function getPaymentMethodList() {
	    	$sql = 'select id, payment_method from payment_method order by payment_method';
	    	$stmt = $this->conn->prepare($sql);
	    	$this->db->checkError();
	    	$stmt->execute();
	    	$this->db->checkError();

	    	$events = array();
	    	$stmt->bind_result($id, $payment_method);
	    	while ($stmt->fetch()) {
	    		$events[] = array('id' => $id, 'payment_method' => $payment_method);
	    	}
	    	$stmt->close();

	    	return $events;
    }

	function jsonData($tablename, $sql) {
	        //$sql = 'SELECT '.$value.' FROM '.$tablename;
	        $stmt = $this->conn->prepare($sql);
	        $this->db->checkError();
	        $stmt->execute();
	        $this->db->checkError();

	        $stmt->bind_result($id, $value);

	        $ids = '"'.$tablename.'_id":["';
	        $values = '"'.$tablename.'_value":["';

	        $row=0;
	        $stmt->store_result();
	        $total = $stmt->num_rows-1;
	        while ($stmt->fetch()) {
	        	$semicolon = ($row!=$total?';':'');
	        	$ids .= $id.':'.$value.$semicolon;
	        	$values .= $value.':'.$value.$semicolon;
	        	//echo $total, $row, $semicolon,$tablename,'<br>';
	        	$row++;
	        }
			$stmt->close();

			$ids .= '"],';
			$values .= '"]';
	        return $ids.$values;
    }

    function jsonAutoCompleteData($tablename, $sql) {
    	//$sql = 'SELECT '.$value.' FROM '.$tablename;
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();

    	$stmt->bind_result($value);

    	$values = '[';

    	$row=0;
    	$stmt->store_result();
    	$total = $stmt->num_rows-1;
    	while ($stmt->fetch()) {
    		$comma = ($row!=$total?',':'');
    		$values .= '"'.$value.'"'.$comma;
    		$row++;
    	}
    	$stmt->close();

    	$values .= ']';
    	return $values;
    }

    function generateJSON() {

    	$jsons = '"active_id":["1:Yes;2:No"],"active_value":["Yes:Yes;No:No"],';
    	$jsons .= Selects::jsonData('user_type', 'select id, user_type value from user_type').',';
    	$jsons .= Selects::jsonData('send_type', 'select id, send_type value from send_type').',';
    	$jsons .= Selects::jsonData('pledge_type', 'select id, pledge_type value from pledge_type').',';
    	$jsons .= Selects::jsonData('payment_type', 'select id, payment_type value from payment_type').',';
    	$jsons .= Selects::jsonData('payment_method', 'select id, payment_method value from payment_method').',';
    	$jsons .= Selects::jsonData('log_table', 'select id, log_table value from log_table').',';
    	$jsons .= Selects::jsonData('log_action', 'select id, log_action value from log_action').',';
    	$jsons .= Selects::jsonData('state', 'select shortname id, longname value from state');

    	return $jsons;
    }
}

?>