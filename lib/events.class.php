<?php

class Events {
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

	function getEventList() {
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
            	'event_date' => Commons::date_format_form($event_date),
            	'location' => $location, 'description' => $description,
            	'target_amount' => $target_amount,
            	'pledge_type_id' => $pledge_type_id,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by, 'modified_by' => $modified_by ,
            	'pledge_type' => $pledge_type);
        }
		$stmt->close();

        return $events;
    }

    function jsonEvent() {
    	$jsonData = $this->select->jsonData('event', 'select id, title value from event');
    	Logger::JSON('event',"{".$jsonData."}");
    }

    function iudEvent($dml, $id, $title, $event_date, $location, $description, $target_amount, $pledge_type_id) {
        $tableName = 'event';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'title'  => array('type' => 's', 'value' => $title),
	            'event_date'     => array('type' => 's', 'value' => Commons::date_format_sql($event_date)),
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
	   	Events::jsonEvent();
        return $id;
    }
}

?>