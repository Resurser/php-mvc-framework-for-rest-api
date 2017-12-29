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
        $httpCode = $success ? 200 : 403;
        http_response_code($httpCode);
        $this->JSON($output);
    }

    public function JSON($data = []) {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');
        echo json_encode($data);
    }

}

// For 4.3.0 <= PHP <= 5.4.0
if (!function_exists('http_response_code')) {

    function http_response_code($newcode = NULL) {
        static $code = 200;
        if ($newcode !== NULL) {
            header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
            if (!headers_sent())
                $code = $newcode;
        }
        return $code;
    }

}