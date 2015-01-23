<?php

class Logs {
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

	function getLogList() {
    	 $sql = 'SELECT
        	l.id, la.log_action, lt.log_table, log, record_id, l.created_on
        FROM
        	log l
        	inner join log_table lt on l.log_table_id = lt.id
    	 	inner join log_action la on l.log_action_id = la.id
    	 order by
    	 		l.created_on desc';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();


        $logs = array();
        $stmt->bind_result($id, $log_action, $log_table, $log, $record_id, $created_on);
        while ($stmt->fetch()) {
            $logs[] = array('id' => $id, 
            	'log_action' => $log_action,
            	'log_table' => $log_table, 
            	'record_id' => $record_id,
            	'log' => $log,
            	'created_on' => Commons::date_format_form($created_on));
        }
		$stmt->close();

        return $logs;
    }

}

?>