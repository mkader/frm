<?php
class DB {
    private $connection;
    private $inTransaction;

    function __construct() {
        $this->openConnection();
    }

    function getConnection() {
        return $this->connection;
    }

    function openConnection() {
        global $config;
        $this->connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if (mysqli_connect_errno()) {
            Logger::log(mysqli_connect_error());
            throw new DBException(mysqli_connect_error());
        }

        $this->connection->set_charset('utf-8');
    }

    function beginTransaction() {
        $this->query('START TRANSACTION');
        $this->inTransaction = true;
    }

    function commit() {
        $this->query('COMMIT');
        $this->inTransaction = false;
    }

    function rollback() {
        $this->query('ROLLBACK');
        $this->inTransaction = false;
    }

    function query($sql) {
        if (!$this->connection->query($sql)) {
            $this->checkError();
        }
    }

    function log($action_id, $table, $id, $data = array()) {
    	if (DB_LOG==1) {
	    	$sdata = array();
	    	//$sdata['id'] = $id;
	    	foreach ($data as $key => $value) {
	    		$sdata[$key] = $value['value'];
	    	}
	    	
	    	if ($table=='user') $table_id = Table::User;
	    	else if ($table=='donator') $table_id = Table::Donator;
	    	else if ($table=='event') $table_id = Table::Event;
	    	else if ($table=='expense') $table_id = Table::Expense;
	    	else if ($table=='payment') $table_id = Table::Payment;
	    	else if ($table=='pledge') $table_id = Table::Pledge;
	    	else if ($table=='pledgeremainder') $table_id = Table::PledgeRemainder;
	    	else if ($table=='member') $table_id = Table::Member;
	    	
	    	// Build the insert SQL statement
	    	$sql = "INSERT INTO log (record_id, log_table_id, log_action_id, log) values(?, ?, ?, ?)";
	
	    	// Create the statement to be executed
	    	$stmt = $this->connection->prepare($sql);
	    	$this->checkError();
	    
	    	$log_data = print_r($sdata,true);
	    	$stmt->bind_param('iiis', $id, $table_id, $action_id, $log_data);
	    	//call_user_func_array(array($stmt, 'bind_param'), $args);
	    	$this->checkError();
	    
	    	// Execute the statement and close
	    	$stmt->execute();
	    	$this->checkError();
	    
	    	$stmt->close();
    	}
    }
    
    function insert($tableName, $data = array()) {
        $colNames = array();
        $values = array();
        $types = array();
        foreach ($data as $key => $value) {
            $colNames[] = $key;
            $values[] = $value['value'];
            $types[] = $value['type'];
        }
        $colNameCount = count($colNames);

        // Build the insert SQL statement
        $sql = "INSERT INTO {$tableName} (";
        $sql .= implode(', ', $colNames);
        $sql .= ") VALUES (";
        for ($i = 0; $i < $colNameCount; $i++) {
            $sql .= "?";
            if ($i != $colNameCount - 1) {
                $sql .= ", ";
            }
        }
        $sql .= ")";

        Logger::log("Insert SQL: " . $sql);

        // Create the statement to be executed
        $stmt = $this->connection->prepare($sql);
        $this->checkError();

        // Build the arguments to be passed to the bind_param method
    	array_unshift($values,implode('', $types));
        
        $args = array();
        foreach ($values as $key => $value)
        {
        	$args[$key] = &$values[$key];
        }
        
        /*$args = array();
        $args[] = &implode('', $types);
        for ($i = 0; $i < count($values); $i++) {
            $args[] = &$values[$i];
        }*/

        call_user_func_array(array($stmt, 'bind_param'), $args);
        $this->checkError();
        
        
        // Execute the statement and close
        $stmt->execute();
        $this->checkError();

        $stmt->close();

        // Get the inserted ID
        $id = $this->connection->insert_id;
        Db::log(Action::Insert, $tableName, $id, $data);
       
        return $id;
    }
    
    function update($tableName, $idValue, $data = array()) {
        $colNames = array();
        $values = array();
        $types = array();
        foreach ($data as $key => $value) {
            $colNames[] = $key;
            $values[] = $value['value'];
            $types[] = $value['type'];
        }
        $colNameCount = count($colNames);

        $sql = "UPDATE {$tableName} SET ";
        for ($i = 0; $i < $colNameCount; $i++) {
            $sql .= $colNames[$i] . " = ?";
            if ($i != $colNameCount - 1) {
                $sql .= ", ";
            }
        }

        // Append the idValue and its type
        $sql .= " WHERE id = ?";
        $types[] = 'i';

        $stmt = $this->connection->prepare($sql);
        $this->checkError();

        $values[] = &$idValue;
        array_unshift($values,implode('', $types));
        
        $args = array();
        foreach ($values as $key => $value)
        {
        	$args[$key] = &$values[$key];
        }
        
        //$args = array();
        //$args[] = &implode('', $types);
        //for ($i = 0; $i < count($values); $i++) {
        //    $args[] = &$values[$i];
        //}
        //$args[] = &$idValue;

        call_user_func_array(array($stmt, 'bind_param'), $args);
        $this->checkError();

        $stmt->execute();
        $this->checkError();
        
        $stmt->close();
        
        Db::log(Action::Update, $tableName, $idValue, $data);
    }

    function delete($tableName, $idValue) {
        $sql = "DELETE FROM {$tableName} WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $this->checkError();
        $stmt->bind_param('i', $idValue);
        $this->checkError();
        $stmt->execute();
        $this->checkError();

        $stmt->close();
        
        Db::log(Action::Delete, $tableName, $idValue, array());
    }

    function checkError() {
        if ($this->connection->errno) {
            $errorNum = $this->connection->errno;
            $error = $this->connection->error;
            if ($this->inTransaction) {
                $this->rollback();
            }
            $this->connection->close();

            throw new DBException("[MySQLi Error #" . $errorNum . "] " . $error);
        }
    }
}

abstract class Table {
	const User  =  1;
	const Donator  =  2;
	const Event  =  3;
	const Expense  =  4;
	const Payment  =  5;
	const Pledge  =  6;
	const PledgeRemainder  =  7;
	const Member  =  8;
	const Metting  =  9;
	const Masjid  =  10;
}

abstract  class Action {
	const Login  =   1;
	const Logout  =  2;
	const Insert  =  3;
	const Update  =  4;
	const Delete  =  5;
}


?>