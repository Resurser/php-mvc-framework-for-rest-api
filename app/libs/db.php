<?php

/*
 * Database utilisation class
 */

class db {

    function __construct() {
        
    }

    private $pdoConnection = null;
    private $columns = "*";
    private $insertColumns = "";
    private $executableInsertValues = "";
    private $printableInsertValues = "";
    private $where = [];
    private $set = "";
    private $table = "";
    private $executableWhere = "";
    private $printableWhere = "";
    private $action = "";
    private $printableSQL = "";
    private $executableSQL = "";
    private $printableSet = " SET ";
    private $insertableValues = [];
    private $executableSet = " SET ";
    private $deleteColumns = "*";

    public function init() {
        $host = $this->config['host'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $database = $this->config['database'];

        try {
            $this->pdoConnection = new PDO('mysql://host=$host;dbname=' . $database . ';', $username, $password, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (Exception $e) {
            die("Failed to connect database: " . $e->getMessage());
        }
    }

    public function select($columns = "*") {
        $this->action = "select";
        $this->columns = $columns;
        return $this;
    }

    public function delete($deleteColumns = "*") {
        $this->action = "delete";
        return $this;
    }

    public function insert($table, $insert = []) {
        $this->table = $table;
        $this->action = "insert";
        $delim = "";
        foreach ($insert as $k => $v) {
            $this->insertColumns .= $delim . "$k";
            $this->executableInsertValues .= $delim . "?";
            $this->printableInsertValues .= $delim . "'$v'";
            $delim = ", ";
            $this->insertableValues[] = $v;
        }
        return $this;
    }

    public function update($set = []) {
        $this->set = $set;
        $this->action = "update";
        $delm = "";
        foreach ($set as $k => $v) {
            $this->executableSet .= $delm . " $k = :$k ";
            $this->printableSet .= $delm . " $k = '$v' ";
            $delm = ", ";
        }
        return $this;
    }

    public function from($table = "") {
        $this->table = $table;
        return $this;
    }

    public function where($where = [], $gate = "AND") {
        $delm = "";
        $this->executableWhere = " WHERE ";
        $this->where = $where;
        $this->printableWhere = $this->executableWhere;
        foreach ($where as $k => $v) {
            $this->executableWhere .= $delm . " $k = :$k ";
            $this->printableWhere .= $delm . " $k = '$v' ";
            $delm = $gate;
        }
        return $this;
    }

    public function makeQuery() {
        switch ($this->action) {
            case "select":
                $this->printableSQL = "SELECT " . $this->columns . " FROM " . $this->table . " " . $this->printableWhere;
                $this->executableSQL = "SELECT " . $this->columns . " FROM " . $this->table . " " . $this->executableWhere;
                break;
            case "update":
                $this->printableSQL = "UPDATE " . $this->table . $this->printableSet . $this->printableWhere;
                $this->executableSQL = "UPDATE " . $this->table . $this->executableSet . $this->executableWhere;
                break;
            case "insert":
                $this->printableSQL = "INSERT INTO " . $this->table . " (" . $this->insertColumns . ") VALUES (" . $this->printableInsertValues . ") ";
                $this->executableSQL = "INSERT INTO " . $this->table . " (" . $this->insertColumns . ") VALUES (" . $this->executableInsertValues . ") ";
                break;
            case "delete":
                $this->printableSQL = "DELETE FROM " . $this->table . $this->printableWhere;
                $this->executableSQL = "DELETE FROM " . $this->table . $this->executableWhere;
                break;
        }
        return $this->executableSQL;
    }

    public function lastQuery($executable = true) {
        return $executable ? $this->executableSQL : $this->printableSQL;
    }

    public function exec($table = "") {
        if ($table != "")
            $this->table = $table;
        else
            $this->hault("Table name is not specified");

        $sql = $this->makeQuery();

        try {
            $statementHandler = $this->pdoConnection->prepare($sql);
            if ($this->action == "insert") {
                return $statementHandler->execute($this->insertableValues);
            } else if ($this->action == "delete") {
                return $statementHandler->execute($this->where);
            } else if ($this->action == "update") {
                return $statementHandler->execute($this->set);
            } else if ($this->action == "select") {
                $statementHandler->execute($this->where);
                return $statementHandler->fetchAll(PDO::FETCH_CLASS, "DataObject");
            }
        } catch (PDOException $e) {
            $this->hault("Error executing database query <br>" . $e->getMessage());
        }
    }

    public function hault($data = "") {
        die("<p><small><b>" . $data . "</b></small></p>");
    }
    public function beginTransaction() {
        $this->pdoConnection->beginTransaction();
    }
    public function commit() {
        $this->pdoConnection->commit();
    }
    public function rollBack() {
        $this->pdoConnection->rollBack();
    }

}

class DataObject {
    
}
