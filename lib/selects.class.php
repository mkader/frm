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

	function jsonData($tablename, $value) {
	        $sql = 'SELECT '.$value.' FROM '.$tablename;
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
    
    function generateJSON() {
    	
    	$jsons = '"active_id":["1:Yes;2:No"],"active_value":["Yes:Yes;No:No"],';
    	$jsons .= Selects::jsonData('user_type', 'id, user_type value').',';
    	$jsons .= Selects::jsonData('send_type', 'id, send_type value').',';
    	$jsons .= Selects::jsonData('pledge_type', 'id, pledge_type value').',';
    	$jsons .= Selects::jsonData('payment_type', 'id, payment_type value').',';
    	$jsons .= Selects::jsonData('payment_method', 'id, payment_method value').',';
    	$jsons .= Selects::jsonData('log_table', 'id, log_table value').',';
    	$jsons .= Selects::jsonData('log_action', 'id, log_action value').',';
    	$jsons .= Selects::jsonData('state', 'shortname id, longname value');
    	
    	return $jsons;
    }
}

?>