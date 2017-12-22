<?php

/*
 * Utilise outputs
 */

class output {

    public function view($view = "", $data = []) {
        foreach ($data as $k => $v) {
            $$k = $v;
        }
        include WORKING_DIRECTORY . "app/views/" . $view . ".php";
    }

    public function REST($data = [], $success = false, $message = "") {
        $output['status'] = $success;
        $output['message'] = $message == "" ? $success ? "Request succeed" : "Request failed" : $message;
        $output['data'] = $data;
        $this->JSON($output);
    }

    public function JSON($data = []) {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');
        echo json_encode($data);
    }

}
