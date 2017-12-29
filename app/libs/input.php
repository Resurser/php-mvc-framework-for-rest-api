<?php

/*
 * Input handler class
 */

class input {

    private $post = [];
    private $get = [];
    private $data = [];

    function __construct() {
        $this->post = $_POST;
        $this->get = $_GET;
        file_get_contents("php://input");
        parse_str(file_get_contents('php://input'), $this->data);
    }

    public function post($key = "") {
        if ($key != "") {
            return $this->post[$key];
        } else {
            return $this->post;
        }
    }

    public function put($key = "") {
        if ($key != "") {
            return $this->data[$key];
        } else {
            return $this->data;
        }
    }

    public function get($key = "") {
        if ($key != "") {
            return $this->get[$key];
        } else {
            return $this->get;
        }
    }

    public function getURIData() {
        return $this->uriData;
    }

}
