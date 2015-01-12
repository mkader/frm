<?php

class Events {
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

	/*
	SELECT 
		id, title, event_date, location, description, target_amount, pledge_type_id, created_on, modified_on, created_by, modified_by
	FROM 
		mcc_fundraise.event;
	*/

    function getEventList() {
    	global $common;
        $sql = 'SELECT
        	e.id, title, event_date, location, description, target_amount, 
        	e.pledge_type_id, created_on, modified_on, created_by, 
        	modified_by, pt.pledge_type 
        FROM
        	event e
        	inner join pledge_type pt on e.pledge_type_id = pt.id';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();


        $events = array();
        $stmt->bind_result($id, $title, $event_date, $location, $description, $target_amount,
        	$pledge_type_id, $created_on, $modified_on, $created_by, $modified_by, $pledge_type);
        while ($stmt->fetch()) {
            $events[] = array('id' => $id, 'title' => $title, 
            	'event_date' => $common->date_format_form($event_date), 
            	'location' => $location, 'description' => $description, 
            	'target_amount' => number_format($target_amount), 
            	'pledge_type_id' => $pledge_type_id,
            	'created_on' => $common->date_format_form($created_on), 
            	'modified_on' => $common->date_format_form($modified_on),
            	'created_by' => $created_by, 'modified_by' => $modified_by , 
            	'pledge_type' => $pledge_type);
        }
		$stmt->fetch();
		$stmt->close();

        return $events;
    }

    function iudEvent($dml, $id, $title, $event_date, $location, $description, $target_amount, $pledge_type_id) {
        global $session, $common;
        $tableName = 'event';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval($session->loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'title'  => array('type' => 's', 'value' => $title),
	            'event_date'     => array('type' => 's', 'value' => $common->date_format_sql($event_date)),
	            'location'     => array('type' => 's', 'value' => $location),
	            'description'     => array('type' => 's', 'value' => $description),
	            'target_amount'     => array('type' => 'i', 'value' => $target_amount),
	            'pledge_type_id'     => array('type' => 'i', 'value' => $pledge_type_id),
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