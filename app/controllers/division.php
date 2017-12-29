<?php

class Division extends RESTController {

    function __construct() {
        parent::__construct();
    }

    public function get($inboundCall = false) {
        if (!$inboundCall)
            $this->core->show_404();
        $objs = $this->model->get();
        $this->output->REST($objs, true, "Request success");
    }

    public function insert($inboundCall = false) {
        if (!$inboundCall)
            $this->core->show_404();
        $data = $this->input->post();
        $this->model->insert($data);
        $this->output->REST([], true, "Request success");
    }

    public function update($inboundCall = false) {
        if (!$inboundCall)
            $this->core->show_404();

        $data = $this->input->put();
        $uriData = $this->input->getURIData();

        if (!is_numeric($uriData[0])) {
            $this->output->REST([], false, "ID is required");
        } else {
            $id = $uriData[0];
            if ($this->model->update($data, ['id' => $id])) {
                $this->output->REST([], true, "Successfully updated!");
            } else {
                $this->output->REST([], true, "Successfully updated!");
            }
        }
    }

    public function index() {
        $requestType = $_SERVER['REQUEST_METHOD'];
        switch ($requestType) {
            case "GET":
                $this->get(true);
                break;
            case "POST":
                $this->insert(true);
                break;
            case "PUT":
                $this->update(true);
                break;
        }
    }

}
