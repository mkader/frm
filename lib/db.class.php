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
        $args = array();
        $args[] = &implode('', $types);
        for ($i = 0; $i < count($values); $i++) {
            $args[] = &$values[$i];
        }

        call_user_func_array(array($stmt, 'bind_param'), $args);
        $this->checkError();

        // Execute the statement and close
        $stmt->execute();
        $this->checkError();

        $stmt->close();

        // Get the inserted ID
        return $this->connection->insert_id;
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

        $args = array();
        $args[] = &implode('', $types);
        for ($i = 0; $i < count($values); $i++) {
            $args[] = &$values[$i];
        }
        $args[] = &$idValue;

        call_user_func_array(array($stmt, 'bind_param'), $args);
        $this->checkError();

        $stmt->execute();
        $this->checkError();
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


?>