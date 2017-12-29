<?php

/*
 * This is the parent of model class
 */

class CoreModel {

    function __construct() {
        
    }

    var $table = "abstract";

    public function loadLibs($libs = []) {
        foreach ($libs as $k => $v) {
            $this->$k = $v;
        }
    }

    public function get() {
        $data = $this->db->select("*", ['status' => 1], "division");
        return $data;
    }

    public function insert($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function update($data = [], $where = []) {
        return $this->db->update($data, $where, $this->table);
    }

}
