<?php

/*
 * Database utilisation class
 */

class db {

    function __construct() {
        
    }

    private $connection = null;
    private $columns = "*";
    private $insertColumns = "";
    private $executableInsertValues = "";
    private $printableInsertValues = "";
    private $set = "";
    private $table = "";
    private $executableWhere = " WHERE ";
    private $printableWhere = " WHERE ";
    private $action = "";
    private $printableSQL = "";
    private $executableSQL = "";
    private $printableSet = " SET ";
    private $executableSet = " SET ";

    public function init() {
        $host = $this->config['host'];
        $username = $this->config['username'];
        $password = $this->config['password'];
        $database = $this->config['database'];

        try {
            $this->connection = new PDO('mysql://host=$host;dbname=' . $database . ';', $username, $password, array(PDO::ATTR_PERSISTENT => true));
        } catch (Exception $e) {
            die("Failed to connect database: " . $e->getMessage());
        }
    }

    public function select($columns = "*") {
        $this->action = "select";
        $this->columns = $columns;
        return $this;
    }

    public function delete() {
        $this->action = "delete";
        return $this;
    }

    public function insert($insert = []) {
        $this->action = "insert";
        $delim = "";
        foreach ($insert as $k => $v) {
            $this->insertColumns .= $delim . "$k";
            $this->executableInsertValues .= $delim . "?";
            $this->printableInsertValues .= $delim . "$v";
            $delim = ", ";
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
    }

    public function lastQuery($executable = true) {
        return $executable ? $this->executableSQL : $this->printableSQL;
    }

    public function exec() {
        $this->makeQuery();
    }

}
