<?php

class Meetings {
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

   	function getMeetingList() {
    	 $sql = 'SELECT
        	e.id, e.meeting_date, e.meeting_time,
        	e.created_on, e.modified_on, e.created_by, e.modified_by
        FROM
        	meeting e
    	 ORDER BY
    	 	e.meeting_date desc';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $meetings = array();
        $stmt->bind_result($id, $meeting_date, $meeting_time,
        	$created_on, $modified_on, $created_by, $modified_by);
        while ($stmt->fetch()) {
            $meetings[] = array('id' => $id,
            	'meeting_time' => $meeting_time,
            	'meeting_date' => Commons::date_format_form($meeting_date),
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by, 'modified_by' => $modified_by);
        }
		$stmt->close();

        return $meetings;
    }

    function iudMeeting($dml, $id, $meeting_date, $meeting_time) {
        $tableName = 'meeting';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'meeting_date'     => array('type' => 's', 'value' => Commons::date_format_sql($meeting_date)),
	            'meeting_time'     => array('type' => 's', 'value' => $meeting_time),
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