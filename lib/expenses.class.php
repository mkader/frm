<?php

class Expenses {
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

   	function getExpenseList() {
    	 $sql = 'SELECT
        	e.id, e.expense_date, e.event_id, e.title, e.comments, e.amount,
        	e.created_on, e.modified_on, e.created_by, e.modified_by,
        	ev.title event_title
        FROM
        	expense e
        	left join event ev on e.event_id = ev.id
    	 ORDER BY
    	 	e.expense_date desc';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $events = array();
        $stmt->bind_result($id, $expense_date, $event_id, $title, $comments, $amount,
        	$created_on, $modified_on, $created_by, $modified_by, $event_title);
        while ($stmt->fetch()) {
            $events[] = array('id' => $id, 
            	'title' => $title,
            	'expense_date' => Commons::date_format_form($expense_date),
            	'comments' => $comments,
            	'amount' => $amount,
            	'event_id' => $event_id,
            	'created_on' => Commons::date_format_form($created_on),
            	'modified_on' => Commons::date_format_form($modified_on),
            	'created_by' => $created_by, 'modified_by' => $modified_by ,
            	'event_title' => $event_title===NULL?"":$event_title);
        }
		$stmt->close();

        return $events;
    }

    function jsonEvent() {
    	$jsonData = $this->select->jsonAutoCompleteData('title', 'select distinct title value from expense order by title asc');
    	Logger::JSON('title',$jsonData);
    }

    function iudExpense($dml, $id, $expense_date, $event_id, $title, $comments, $amount) {
        $tableName = 'expense';
        //$timestamp = date('Y-m-d H:i:s');
        $login_id = @intval(Sessions::loginUserID());
        if ($dml!='d') {
        	$data = array(
	            'title'  => array('type' => 's', 'value' => $title),
	            'expense_date'     => array('type' => 's', 'value' => Commons::date_format_sql($expense_date)),
	            'event_id'     => array('type' => 's', 'value' => $event_id),
	            'comments'     => array('type' => 's', 'value' => $comments),
	            'amount'     => array('type' => 'i', 'value' => $amount),
	            'modified_by'   => array('type' => 'i', 'value' => $login_id)
	        );
        }

       	if ($dml=='i') {
       		$data['created_by'] = array('type' => 'i', 'value' => $login_id);
       		$id = $this->db->insert($tableName, $data);
       	}
       	else if ($dml=='u') $this->db->update($tableName, $id, $data);
	   	else if ($dml=='d') $this->db->delete($tableName, $id);
	   	Expenses::jsonEvent();
        return $id;
    }
}

?>